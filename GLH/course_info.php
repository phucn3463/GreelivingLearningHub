<?php
session_start();
require_once("settings.php");

if(!isset($_SESSION['loggedIn'])){
    header('location:index.html');
}

$username = $_SESSION['username'];
$candidateId = $_SESSION['candidateId'];

$courseId = isset($_GET['id']) ? $_GET['id'] : null;
if ($courseId === null) {
    header("Location: error_page.php");
    exit();
}

$conn = @mysqli_connect($host, $user, $pwd, $sql_db) or die ("Unable to connect");

$checkQuery = "SELECT id FROM tblcourseregistration WHERE courseId = $courseId AND candidateId = $candidateId";
$checkResult = mysqli_query($conn, $checkQuery);

if ($checkResult && mysqli_num_rows($checkResult) > 0) {
    // Job application exists, redirect to the job application detail page
    $courseRegistrationRow = mysqli_fetch_assoc($checkResult);
    $courseRegistrationId = $courseRegistrationRow['id'];
    header("Location: learning_course_detail.php?courseregistrationId=$courseRegistrationId");
    exit();
}


$courseQuery = "SELECT tblcourse.id, tblcourse.title, tblcoursespecialization.title AS specialization, tblcourse.description, tblcourse.length, tblcourse.price
FROM tblcourse
JOIN tblcoursespecialization ON tblcoursespecialization.id = tblcourse.specializationId
WHERE tblcourse.id = $courseId";
$courseQueryResult = mysqli_query($conn, $courseQuery) or die('Course query failed');
$courseQueryRow = mysqli_fetch_assoc($courseQueryResult);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Detail</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <?php
        include 'includes/header_candidate.inc';
    ?>
    <main>
        <h1>Course's detail</h1>
        
        <div>
            <h2>Course title: <?php echo $courseQueryRow['title']; ?></h2>
            <p>Specialization: <?php echo $courseQueryRow['specialization']; ?></p>
            <p>Length: <?php echo $courseQueryRow['length']; ?></p>

            <?php
            // Check if all rows related to this course have rate = null
            $checkRateQuery = "SELECT COUNT(*) AS totalRows, SUM(rate) AS totalRate FROM tblcourseregistration WHERE courseId = " . $courseQueryRow['id'] . " AND rate IS NOT NULL";
            $checkRateResult = mysqli_query($conn, $checkRateQuery) or die('Check rate query failed');
            $checkRateRow = mysqli_fetch_assoc($checkRateResult);

            if ($checkRateRow['totalRows'] == 0) {
                echo "<p>Average Rating: This course has not received any ratings yet.</p>";
            } else {
                // Calculate the average rating
                $averageRate = $checkRateRow['totalRate'] / $checkRateRow['totalRows'];
                echo "<p>Average Rating: " . number_format($averageRate, 2) . "/5</p>";
            }
            ?>

            <p>Description: <?php echo $courseQueryRow['description']; ?></p>
            <p>Price: <?php echo $courseQueryRow['price']; ?></p>
        </div>

        <div>

        </div>
            <h2>Course review</h2>
            <?php
                $getCommentsQuery = "SELECT tblcandidate.firstName, tblcandidate.lastName, tblcoursereview.review FROM tblcoursereview JOIN tblcandidate ON tblcoursereview.candidateid = tblcandidate.id WHERE tblcoursereview.courseId = " . $courseQueryRow['id'];
                $getCommentsResult = mysqli_query($conn, $getCommentsQuery) or die('Get comments query failed');

                echo "<p>Previous Comment: </p>";
                echo "<ul>";
                if (mysqli_num_rows($getCommentsResult) > 0) {
                    // Display all previous comments
                    while ($commentRow = mysqli_fetch_assoc($getCommentsResult)) {
                        echo "<li>" . $commentRow['firstName'] . " " . $commentRow['lastName'] . ": " . $commentRow['review'] . "</li>";
                    }
                } else {
                    // No previous comments
                    echo "<li>No previous comments.</li>";
                }
                echo "</ul>";
            ?>
        <div>
            <a href="register_course.php?courseId=<?php echo $courseQueryRow['id']; ?>">Register for this course</a>
        </div>

    </main>
    <?php
        include 'includes/footer.inc';
    ?>
</body>
</html>
