<?php
session_start();
require_once("settings.php");

if (!isset($_SESSION['loggedIn'])) {
    header('location:index.html');
}

$conn = @mysqli_connect($host, $user, $pwd, $sql_db) or die("Unable to connect");


$jobId = $_GET['jobId'];

// Check if the applicationId is set in the URL
if (!isset($jobId)) {
    // Redirect to an error page or handle the situation accordingly
    header('location:error_page.php');
    exit();
}

$jobQuery = "SELECT * FROM `tbljob` WHERE id = $jobId";
$jobQueryResult = mysqli_query($conn, $jobQuery);
$jobQueryDetail = mysqli_fetch_assoc($jobQueryResult);

// Fetch application details
$applicationQuery = "SELECT tbljobapplication.id AS jobApplicationId, tbljobapplication.status, tbljobapplication.cv, tblcandidate.id AS candidateId, tblcandidate.firstName, tblcandidate.lastName, tblcandidate.birthdate, tblcandidate.sex, tblcandidate.phone, tblcandidate.email 
                    FROM tbljobapplication 
                    JOIN tblcandidate ON tbljobapplication.candidateId = tblcandidate.id
                    WHERE jobId = $jobId";
$applicationResult = mysqli_query($conn, $applicationQuery);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Application</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <?php
        include 'includes/header_employer.inc';
    ?>

    <main>
        <div class="job-application">
            <h1>Job applications</h1>
            <div class="job-application-section">
                <div class="job-application-detail">
                    <h2>Job's details</h2>
                    <div class="job-application-details">
                        <p>Title: <?php echo $jobQueryDetail['title']; ?></p>
                        <p>Experience: <?php echo $jobQueryDetail['experienceLevel']; ?></p>
                        <p>Working format: <?php echo $jobQueryDetail['workingFormat']; ?></p>
                        <p>Salary: <?php echo $jobQueryDetail['salaryFrom'] . ' - ' . $jobQueryDetail['salaryTo']; ?></p>
                        <p>Available slot: <?php echo $jobQueryDetail['slot']; ?></p>
                    </div> 

                    <h2>Candidate Applications</h2>
                    <?php
                        if (mysqli_num_rows($applicationResult) == 0) {
                            echo "<div>No application to display<div/>";
                        }
                        else {
                            while ($applicationDetails = mysqli_fetch_assoc($applicationResult)) {
                                echo "<div class='job-application-details'>";
                                echo "<p>Application ID: " . $applicationDetails['jobApplicationId'] . "</p>";
                                echo "<p>Candidate name: " . $applicationDetails['firstName'] . $applicationDetails['lastName'] . "</p>";
                                echo "<p>Candidate birthdate: " . $applicationDetails['birthdate'] . "</p>";
                                echo "<p>Candidate sex: " . $applicationDetails['sex'] . "</p>";
                                echo "<p>Candidate phone number: " . $applicationDetails['phone'] . "</p>";
                                echo "<p>Candidate email: " . $applicationDetails['email'] . "</p>";
                                echo "<p>Candidate experiences: </p>";
                                echo "<ul>";

                                $candidateExpQuery = "SELECT * FROM tblcandidateexperience WHERE candidateId = " . $applicationDetails['candidateId'];
                                $candidateExpResult = mysqli_query($conn, $candidateExpQuery);

                                if (mysqli_num_rows($candidateExpResult) == 0) {
                                    echo "<li>No experience to display</li>";
                                }
                                else {
                                    while ($candidateExpDetails = mysqli_fetch_assoc($candidateExpResult)) {
                                        echo "<li>{$candidateExpDetails['role']} at {$candidateExpDetails['organization']} from {$candidateExpDetails['dateStart']} to {$candidateExpDetails['dateEnd']}</li>";
                                    }     
                                }
                                                
                                echo "</ul>";
                                
                                echo "<p>Application CV: <a href='uploads/" . $applicationDetails['cv'] . "' class='job-application-cv'>View CV</a></p>";
                                echo "<p>Application status: " . $applicationDetails['status'] . " <a href='change_status.php?applicationId={$applicationDetails['jobApplicationId']}' class='job-application-status'>Change status</a> </p>";
                                if ($applicationDetails['status'] == 'Awaiting Interview') {
                                    $appointmentQuery = "SELECT * FROM `tbljobapplicationappointment` WHERE jobApplicationId = {$applicationDetails['jobApplicationId']}";
                                    $appointmentQueryResult = mysqli_query($conn, $appointmentQuery);
                                
                                    if (!$appointmentQueryResult) {
                                        // Query failed, handle the error (display or log the error message)
                                        echo "Error: " . mysqli_error($conn);
                                    } else {
                                        // Fetch the appointment details
                                        $appointmentRow = mysqli_fetch_assoc($appointmentQueryResult);
                                
                                        if ($appointmentRow) {
                                            // Display the appointment details
                                            echo "<p>Appointment: " . $appointmentRow['appointmentStart'] . " - " . $appointmentRow['appointmentEnd'] . " <a href='change_appointment.php?jobApplicationId={$appointmentRow['jobApplicationId']}' class='job-application-status'>Change appointment</a> </p>";
                                        } else {
                                            // No appointment found
                                            echo "<p>No appointment to display</p>";
                                        }
                                    }
                                }                        
                                echo "</div>";
                            }
                        }
                    ?>
                </div>
            </div>
        </div>
    </main>

    <?php
        include 'includes/footer.inc';
    ?>
</body>
</html>

<?php
    mysqli_close($conn);
?>