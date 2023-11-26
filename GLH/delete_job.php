<?php
session_start();
require_once("settings.php");

if (!isset($_SESSION['loggedIn'])) {
    header('location:index.php');
}

// Check if the jobId is set in the URL
if (!isset($_GET['jobId'])) {
    // Redirect to an error page or handle the situation accordingly
    header('location:error_page.php');
    exit();
}

$jobId = $_GET['jobId'];

$conn = @mysqli_connect($host, $user, $pwd, $sql_db) or die("Unable to connect");

// Check if the job exists
$checkJobQuery = "SELECT * FROM tbljob WHERE id = $jobId";
$checkJobResult = mysqli_query($conn, $checkJobQuery);

if (mysqli_num_rows($checkJobResult) == 0) {
    // Job not found, redirect to an error page or handle accordingly
    header('location:error_page.php');
    exit();
}

// Perform deletion
$deleteJobQuery = "DELETE FROM tbljob WHERE id = $jobId";
$deleteJobResult = mysqli_query($conn, $deleteJobQuery);

if ($deleteJobResult) {
    // Deletion successful, redirect to a success page or a relevant location
    header('location:employer.php');
} else {
    // Deletion failed, redirect to an error page or handle accordingly
    header('location:error_page.php');
}

mysqli_close($conn);
?>
