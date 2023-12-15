<?php
session_start();

require_once("settings.php");
$conn = @mysqli_connect($host, $user, $pwd, $sql_db) or die ("Unable to connect");

if(!isset($_SESSION['loggedIn'])){
    header('location:index.php');
}

$canId = $_SESSION['candidateId'];
$courseRegistrationId = $_GET['courseregistrationId'];

$learningCourseDetailQuery = "SELECT tblcourse.id, tblcourse.title, tblcoursespecialization.title AS specialization, tblcourse.description, tblcourse.length, tblcourseregistration.status
FROM tblcourse
JOIN tblcoursespecialization ON tblcoursespecialization.id = tblcourse.specializationId
JOIN tblcourseregistration ON tblcourse.id = tblcourseregistration.courseId
WHERE tblcourseregistration.id = $courseRegistrationId";
$learningCourseDetailResult = mysqli_query($conn, $learningCourseDetailQuery) or die('Query failed');
$learningCourseRow = mysqli_fetch_assoc($learningCourseDetailResult);



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course detail informations</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <?php
        include 'includes/header_candidate.inc';
    ?>
    <main class='learningcoursedetail'>
    <h1>Course detail informations</h1>

    <div>
        <h2>Course title: <?php echo $learningCourseRow['title']; ?></h2>
        <p>Specialization: <?php echo $learningCourseRow['specialization']; ?></p>
        <p>Length: <?php echo $learningCourseRow['length']; ?></p>

        <?php
        // Check if all rows related to this course have rate = null
        $checkRateQuery = "SELECT COUNT(*) AS totalRows, SUM(rate) AS totalRate FROM tblcourseregistration WHERE courseId = " . $learningCourseRow['id'] . " AND rate IS NOT NULL";
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

        <p>Description: <?php echo $learningCourseRow['description']; ?></p>
    </div>

    <div>
        <h2>My study progress</h2>
        <?php
            if ($learningCourseRow['status'] === 'Passed' || $learningCourseRow['status'] === 'Failed') {
                $selectCoursesRegistrationScoreQuery = "SELECT tblcourseregistration.score FROM tblcourseregistration WHERE tblcourseregistration.id = $courseRegistrationId";
                $selectCoursesRegistrationScoreResult = mysqli_query($conn, $selectCoursesRegistrationScoreQuery) or die('Query failed');
                $selectCoursesRegistrationScoreRow = mysqli_fetch_assoc($selectCoursesRegistrationScoreResult);

                echo "<p>Score: " . $selectCoursesRegistrationScoreRow['score'] . "</p>";
            }
        ?>
        <p>Learning status: <?php echo $learningCourseRow['status']; ?></p>
    </div>

    <div>
        <h2>Course review</h2>

        <?php
        // Check if the user has already rated the course
        $checkUserRateQuery = "SELECT rate FROM tblcourseregistration WHERE courseId = $courseRegistrationId AND candidateId = $canId";
        $checkUserRateResult = mysqli_query($conn, $checkUserRateQuery) or die('Check user rate query failed');
        $userRateRow = mysqli_fetch_assoc($checkUserRateResult);

        if ($userRateRow) {
            // User has already rated the course
            echo "<p>My rate: " . $userRateRow['rate'] . "</p>";
        } else {
            // User has not rated the course, display a form to post the rate
            echo "<form method='post' action='process_rate.php'>"; // Assuming 'process_rate.php' is your form handling script
            echo "<label for='rate'>Rate this course:</label>";
            echo "<input type='range' name='rate' id='rate' min='1' max='5' step='1' value='3' required>";
            echo "<span id='rateValue'>3</span>"; // Display the current selected value
            echo "<input type='hidden' name='courseRegistrationId' value='$courseRegistrationId'>";
            echo "<input type='submit' class='ratebutton' value='Rate'>";
            echo "</form>";
            
            // JavaScript to dynamically update the displayed value
            echo "<script>
                    const rateInput = document.getElementById('rate');
                    const rateValue = document.getElementById('rateValue');
                    
                    rateInput.addEventListener('input', function() {
                        rateValue.textContent = rateInput.value;
                    });
                </script>";
        }
        ?>
        <!-- A form to post new comments here -->
        <form method="post" action="process_comment.php"> <!-- Assuming 'process_comment.php' is your form handling script -->
            <label for="comment">Your comment:</label>
            <textarea name="comment" id="comment" rows="1"  required></textarea>
            <input type="hidden" name="courseRegistrationId" value="<?php echo $courseRegistrationId; ?>">
            <input type="submit" class='ratebutton' value="Comment">
        </form>

        <!-- Display all previous comments -->
        <?php
        $getCommentsQuery = "SELECT tblcandidate.firstName, tblcandidate.lastName, tblcoursereview.review FROM tblcoursereview JOIN tblcandidate ON tblcoursereview.candidateid = tblcandidate.id WHERE tblcoursereview.courseId = " . $learningCourseRow['id'];
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
    </div>

    </main>
    <?php
        include 'includes/footer.inc';
    ?>
</body>
</html>