<?php
// Ensure the form is submitted
if (isset($_POST['applyJob'])) {
    // Database connection and other necessary configurations
    require_once("settings.php");
    $conn = @mysqli_connect($host, $user, $pwd, $sql_db) or die("Unable to connect");

    // Get user input
    $jobId = mysqli_real_escape_string($conn, $_POST['jobId']);
    $candidateId = mysqli_real_escape_string($conn, $_POST['candidateId']);

    // File upload handling
    $targetDirectory = "uploads/";
    $originalFileName = basename($_FILES["resume"]["name"]);
    $fileType = pathinfo($originalFileName, PATHINFO_EXTENSION);

    // Generate a unique name using timestamp and original file name
    $uniqueFileName = time() . '_' . $originalFileName;
    $targetFile = $targetDirectory . $uniqueFileName;

    // Check if the file is a PDF
    if ($fileType == "pdf") {
        // Move the uploaded file to the specified directory
        if (move_uploaded_file($_FILES["resume"]["tmp_name"], $targetFile)) {
            // File upload successful, now insert data into the database

            //SQL TRANSACTION
            mysqli_begin_transaction($conn);
            $insertQuery = "INSERT INTO tbljobapplication (jobId, candidateId, cv) VALUES ('$jobId', '$candidateId', '$targetFile')";
            $insertQueryResult = mysqli_query($conn, $insertQuery);
            $updateQuery = "UPDATE tbljob SET slot = (slot - 1) WHERE id = $jobId";
            $updateQueryResult = mysqli_query($conn, $updateQuery);
    
            if ($insertQueryResult && $updateQueryResult) {
                mysqli_commit($conn);
    
                // Get the ID of the inserted row
                $getIdQuery = "SELECT id FROM tbljobapplication WHERE jobId = $jobId AND candidateId = $candidateId";
                $getIdQueryResult = mysqli_query($conn, $getIdQuery);
                $getIdQueryRow = mysqli_fetch_assoc($getIdQueryResult);
    
                // Check if the result is not NULL and if it has the 'id' index
                if ($getIdQueryRow && array_key_exists('id', $getIdQueryRow)) {
                    // Redirect to the job application detail page with the obtained ID
                    header("Location: job_application_detail_candidate.php?jobApplicationId={$getIdQueryRow['id']}");
                } else {
                    // Handle the case where 'id' is not present in the result
                    echo "Error obtaining job application ID.";
                }
            } else {
                mysqli_rollback($conn);
                echo "Error inserting data into the database: " . mysqli_error($conn);
            }
        } else {
            echo "Error uploading file.";
        }
    } else {
        echo "Only PDF files are allowed.";
    }
    

    // Close the database connection
    mysqli_close($conn);
}
?>
