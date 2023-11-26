<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedIn'])) {
    header('location:index.php');
    exit();
}

require_once("settings.php");

$conn = @mysqli_connect($host, $user, $pwd, $sql_db) or die ("Unable to connect");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize the rate
    $rate = intval($_POST['rate']);

    // Get the course registration ID from the form
    $courseRegistrationId = $_POST['courseRegistrationId'];

    // Update the rate in the database
    $updateRateQuery = "UPDATE tblcourseregistration SET rate = $rate WHERE id = $courseRegistrationId";
    
    if (mysqli_query($conn, $updateRateQuery)) {
        header("location: learning_course_detail.php?courseregistrationId=$courseRegistrationId");
    } else {
        echo "Error updating rate: " . mysqli_error($conn);
    }

    // Close the database connection
    mysqli_close($conn);
} else {
    // Redirect if the form is not submitted
    header('location: learning_course_detail.php?courseregistrationId=$courseRegistrationId');
    exit();
}
?>
