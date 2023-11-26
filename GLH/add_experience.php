<?php
session_start();

require_once("settings.php");
$conn = @mysqli_connect($host, $user, $pwd, $sql_db) or die("Unable to connect");

if (!isset($_SESSION['loggedIn'])) {
    header('location:index.php');
}

$canId = $_SESSION['candidateId'];

// Check if the form for adding a new experience is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize the input (you may want to add more validation)
    $dateStart = mysqli_real_escape_string($conn, $_POST['dateStart']);
    $dateEnd = mysqli_real_escape_string($conn, $_POST['dateEnd']);
    $organization = mysqli_real_escape_string($conn, $_POST['organization']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);

    // Check if the start date is greater than the end date
    if (strtotime($dateStart) > strtotime($dateEnd)) {
        echo "Error: Start date cannot be greater than end date.";
        exit();
    }

    // Insert the new experience into the database
    $insertExperienceQuery = "INSERT INTO tblcandidateexperience (candidateId, dateStart, dateEnd, organization, role) VALUES ($canId, '$dateStart', '$dateEnd', '$organization', '$role')";
    
    if (mysqli_query($conn, $insertExperienceQuery)) {
        // Redirect to the page showing all experiences
        header("location: online_cv.php");
        exit();
    } else {
        echo "Error adding new experience: " . mysqli_error($conn);
    }
}
?>
