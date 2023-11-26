<?php
session_start();
require_once("settings.php");
if(!isset($_SESSION['loggedIn'])){
    header('location:index.html');
    exit();
}

$jobApplicationId = $_GET['jobApplicationId'];

$conn = @mysqli_connect($host, $user, $pwd, $sql_db) or die ("Unable to connect");

$jobIdQuery = "SELECT jobId FROM tbljobapplication WHERE id = $jobApplicationId";
$jobIdResult = mysqli_query($conn, $jobIdQuery);
$jobIdRow = mysqli_fetch_assoc($jobIdResult);

$jobId = $jobIdRow['jobId'];

$existingAppointmentQuery = "SELECT * FROM tbljobapplicationappointment WHERE jobApplicationId = $jobApplicationId";
$existingAppointmentResult = mysqli_query($conn, $existingAppointmentQuery);
$existingAppointmentDetails = mysqli_fetch_assoc($existingAppointmentResult);

$existingAppointmentStart = '';
$existingAppointmentEnd = '';

if (isset($existingAppointmentDetails['appointmentStart'])) {
    $existingAppointmentStart = $existingAppointmentDetails['appointmentStart'];
}

if (isset($existingAppointmentDetails['appointmentEnd'])) {
    $existingAppointmentEnd = $existingAppointmentDetails['appointmentEnd'];
}

if (isset($_GET["update_appointment"]))
{
    $astart = mysqli_real_escape_string($conn, $_GET['appointment_start']);
    $aend = mysqli_real_escape_string($conn, $_GET['appointment_end']);  

    // Remove the "T" from the datetime-local format
    $astart = str_replace("T", " ", $astart);
    $aend = str_replace("T", " ", $aend);

    // Check if start time is smaller than end time
    if ($astart < $aend) 
    {
        if (mysqli_num_rows($existingAppointmentResult) > 0) 
        {
            // Update the existing appointment
            $updateQuery = "UPDATE tbljobapplicationappointment SET appointmentStart = '$astart', appointmentEnd = '$aend' WHERE jobApplicationId = $jobApplicationId";
            $updateResult = mysqli_query($conn, $updateQuery);
        } 
        else 
        {
            // Insert a new row in tbljobapplicationappointment
            $insertQuery = "INSERT INTO tbljobapplicationappointment (jobApplicationId, appointmentStart, appointmentEnd) VALUES ($jobApplicationId, '$astart', '$aend')";
            $insertResult = mysqli_query($conn, $insertQuery);
        }

        if ($updateResult || $insertResult) 
        {
            header("location: view_applications.php?jobId=$jobId");
            exit();
        } 
        else 
        {
            header("location: error_page.php");
            exit();
        }
    } 
    else 
    {
        // Handle the case where start time is not smaller than end time
        header("location: error_page.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Application Appointment</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <?php
        include 'includes/header_employer.inc';
    ?>
    <main>
        <h1>Set Appointment</h1>

        <form action='change_appointment.php' method='GET'>
            <input type="hidden" name="jobApplicationId" value="<?php echo $jobApplicationId; ?>">

            <label for="appointment_start">Appointment start:</label>
            <input type="datetime-local" name="appointment_start" required id="appointment_start" value="<?php echo $existingAppointmentStart; ?>"></br>
            <label for="appointment_end">Appointment end:</label>
            <input type="datetime-local" name="appointment_end" required id="appointment_end" value ="<?php echo $existingAppointmentEnd; ?>"></br>
            <input type='submit' name='update_appointment' value='Update appointment interview'>
        </form>
    </main>
    <?php
        include 'includes/footer.inc';
    ?>
</body>
</html>