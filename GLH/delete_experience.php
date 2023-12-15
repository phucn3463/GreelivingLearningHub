<?php
session_start();

require_once("settings.php");
$conn = @mysqli_connect($host, $user, $pwd, $sql_db) or die("Unable to connect");

if (!isset($_SESSION['loggedIn'])) {
    header('location:index.php');
}

$canId = $_SESSION['candidateId'];

// Check if the experience ID is provided in the URL
if (isset($_GET['id'])) {
    $experienceId = $_GET['id'];

    // Delete the experience from the database
    $deleteExperienceQuery = "DELETE FROM tblcandidateexperience WHERE id = $experienceId AND candidateId = $canId";

    if (mysqli_query($conn, $deleteExperienceQuery)) {
        // Redirect to the page showing all experiences
        header("location: online_cv.php");
        exit();
    } else {
        echo "Error deleting experience: " . mysqli_error($conn);
    }
} else {
    echo "Experience ID not provided.";
    exit();
}
?>
