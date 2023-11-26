<?php
session_start();

require_once("settings.php");
$conn = @mysqli_connect($host, $user, $pwd, $sql_db) or die ("Unable to connect");

$username = $_SESSION['username'];

$canQuery = "SELECT * FROM tbluser WHERE username = '$username'";
$canQueryResult = mysqli_query($conn, $canQuery) or die('Query failed');
$canRow = mysqli_fetch_assoc($canQueryResult);
$canId = $canRow['candidateId'];

$_SESSION['candidateId'] = $canId;

if(!isset($_SESSION['loggedIn'])){
    header('location:index.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <?php
        include 'includes/header_candidate.inc';
    ?>
    <div>
        <?php
            $nameQuery = "SELECT firstName, lastName FROM tblcandidate WHERE id = $canId";
            $nameQueryResult = mysqli_query($conn, $nameQuery) or die('Name query failed');
            $nameRow = mysqli_fetch_assoc($nameQueryResult)
        ?>
        <h1>Welcome, <?php echo $nameRow['firstName'] . ' ' . $nameRow['lastName']; ?>!</h1>
        <div>
            <h2>My Dashboard</h2>
            <div>
                <h3>My learning courses</h3>
                <?php 
                    $course_query = "SELECT tblcourseregistration.id, tblcourse.title, tblcoursespecialization.title AS specialization, tblcourse.length, tblcourseregistration.score, tblcourseregistration.status
                    FROM tblcourseregistration
                    JOIN tblcourse ON tblcourse.id = tblcourseregistration.courseId
                    JOIN tblcoursespecialization ON tblcoursespecialization.id = tblcourse.specializationId
                    WHERE tblcourseregistration.candidateId = $canId;";
                    $course_result = mysqli_query($conn, $course_query) or die('Course query failed');
                    
                    if (mysqli_num_rows($course_result) > 0) {
                        while ($course_row = mysqli_fetch_assoc($course_result)) {
                            echo "<div>"; 
                            echo "<p>" . $course_row['title'] . "</p>";
                            echo "<p>Specialization: " . $course_row['specialization'] . "</p>";
                            echo "<p>Length: " . $course_row['length'] . "</p>";
                            echo "<p>Score: " . $course_row['score'] . "</p>";
                            echo "<p>Status: " . $course_row['status'] . "</p>";
                            echo "<a href='learning_course_detail.php?courseregistrationId=" . $course_row['id'] . "'>View detail </a>";
                            echo '</div>';
                        }
                    } else {
                        echo "<div><p>There is no course to display.</p></div>";
                    }
                ?>
            </div>

            <div>
                <h3>My job applications</h3>
                <?php 
                    $job_application_query = "SELECT tbljob.title, tbljobapplication.id, tbljobspecialization.title AS specialization, tbljobapplication.status, tbljob.workingFormat, tbljob.experienceLevel
                    FROM tbljob
                    JOIN tbljobapplication ON tbljobapplication.jobId = tbljob.id
                    JOIN tbljobspecialization ON tbljob.specializationId = tbljobspecialization.id
                    WHERE tbljobapplication.candidateId = $canId;";
                    $job_application_result = mysqli_query($conn, $job_application_query) or die('Job query failed');
                    
                    if (mysqli_num_rows($job_application_result) > 0) {
                        while ($job_application_row = mysqli_fetch_assoc($job_application_result)) {
                            echo "<div>"; 
                            echo "<p>" . $job_application_row['title'] . "</p>";
                            echo "<p>Application ID: " . $job_application_row['id'] . "</p>";
                            echo "<p>Specialization: " . $job_application_row['specialization'] . "</p>";
                            echo "<p>Status: " . $job_application_row['status'] . "</p>";
                            if ($job_application_row['status'] == 'Awaiting Interview') {
                                $appointment_query = "SELECT appointmentStart, appointmentEnd FROM `tbljobapplicationappointment` WHERE jobApplicationId = {$job_application_row['id']}";
                                $appointment_query_result = mysqli_query($conn, $appointment_query) or die('Appointment query failed');
                                $appointment_row = mysqli_fetch_assoc($appointment_query_result);
                                echo "<p>Appointment: " . $appointment_row['appointmentStart'] . " - " . $appointment_row['appointmentEnd'] . "</p>";
                            }                            
                            echo "<p>Working format: " . $job_application_row['workingFormat'] . "</p>";
                            echo "<p>Experience level: "  . $job_application_row['experienceLevel'] . "</p>";
                            echo "<a href='job_application_detail_candidate.php?jobApplicationId=" . $job_application_row['id'] . "'>View detail </a>";
                            echo '</div>';
                        }
                    } else {
                        echo "<div><p>There is no job application to display.</p></div>";
                    }
                ?>
            </div>

            <div>
                <h3>My online CV</h3>
                <?php 
                    $candidate_experience_query = "SELECT tblcandidateexperience.dateStart, tblcandidateexperience.dateEnd, tblcandidateexperience.role, tblcandidateexperience.organization
                    FROM tblcandidateexperience
                    WHERE tblcandidateexperience.candidateId = $canId;";
                    $candidate_experience_result = mysqli_query($conn, $candidate_experience_query) or die('Job query failed');
                    
                    echo "<div>";

                    if (mysqli_num_rows($candidate_experience_result) > 0) {

                        echo "<ul>"; 

                        while ($candidate_experience_row = mysqli_fetch_assoc($candidate_experience_result)) {
                            echo "<li>{$candidate_experience_row['role']} at {$candidate_experience_row['organization']} from {$candidate_experience_row['dateStart']} to {$candidate_experience_row['dateEnd']}</li>";
                        }

                        echo '</ul>';

                    } else {
                        echo "<p>There is no experience to display.</p>";
                    }

                    echo "<a href='online_cv.php?candidateId=" . $canId . "'>Edit my online CV</a>";
                    echo "</div>";
                ?>
            </div>
        </div>
    </div>
    <?php
        include 'includes/footer.inc';
    ?>
</body>
</html>