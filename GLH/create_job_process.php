<?php 
    session_start();
    require_once("settings.php");
    $conn = @mysqli_connect($host, $user, $pwd, $sql_db) or die ("Unable to connect");

    $username = $_SESSION['username'];
    $employerId = $_SESSION['employerId'];

    $newJob_title = $_POST['newJob_title'];
    $newJob_description = $_POST['newJob_description'];
    $newJob_deadline = $_POST['newJob_deadline'];
    $newJob_salaryFrom = $_POST['newJob_salaryFrom'];
    $newJob_salaryTo = $_POST['newJob_salaryTo'];
    $newJob_specializationId = $_POST['newJob_specializationId'];
    $newJob_workingFormat = $_POST['newJob_workingFormat'];
    $newJob_expLevel = $_POST['newJob_expLevel'];
    $newJob_slot = $_POST['newJob_slot'];


    if(isset($_POST['create_job'])) {
        if (!empty($newJob_title) && !empty($newJob_description) && !empty($newJob_deadline) && !empty($newJob_salaryFrom) && !empty($newJob_salaryTo) && !empty($newJob_specializationId) && !empty($newJob_workingFormat) && !empty($newJob_expLevel) && !empty($newJob_slot)) {
            if ($newJob_salaryFrom < $newJob_salaryTo) {
                $query = mysqli_query($conn, "INSERT INTO tbljob (title, description, employerId, deadline, salaryFrom, salaryTo, 
                    specializationId, workingFormat, experienceLevel, slot) VALUES ('$newJob_title', '$newJob_description', '$employerId', '$newJob_deadline', '$newJob_salaryFrom', '$newJob_salaryTo', '$newJob_specializationId', '$newJob_workingFormat', '$newJob_expLevel', '$newJob_slot')");
            
                if ($query) {
                    $lastInsertedId = mysqli_insert_id($conn);
                    header("Location: job_detail_employer.php?jobId=$lastInsertedId");
                }
            } else {
                header("Location: creation.php?salaryerror");
            }
            
        } else {
            header("Location: creation.php?missinginfo");
        }
    } else {
        header("Location: creation.php?failedtocreatejob");
    }
?>