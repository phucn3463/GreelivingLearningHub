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
    // Validate and sanitize the comment
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);

    // Get the course registration ID from the form
    $courseRegistrationId = $_POST['courseRegistrationId'];

    $get_info_query = "SELECT candidateId, courseId FROM tblcourseregistration WHERE id = $courseRegistrationId";
    $get_info_result = mysqli_query($conn, $get_info_query);
    $get_info_row = mysqli_fetch_assoc($get_info_result);

    // Insert the comment into the database
    $insertCommentQuery = "INSERT INTO tblcoursereview (candidateId, courseId, review) VALUES (" . $get_info_row['candidateId'] . ", " . $get_info_row['courseId'] . ", '" . $comment . "')";

    if (mysqli_query($conn, $insertCommentQuery)) {
        header("location: learning_course_detail.php?courseregistrationId=" . $courseRegistrationId);
    } else {
        echo "Error adding comment: " . mysqli_error($conn);
    }

    // Close the database connection
    mysqli_close($conn);
} else {
    // Redirect if the form is not submitted
    header('location: learning_course_detail.php?courseregistrationId=" . $courseRegistrationId');
    exit();
}

?>
