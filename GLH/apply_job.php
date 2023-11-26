<?php
session_start();
require_once("settings.php");

if(!isset($_SESSION['loggedIn'])){
    header('location:index.html');
}

$username = $_SESSION['username'];
$candidateId = $_SESSION['candidateId'];
$jobId = $_GET['jobId'];


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Detail</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <?php
        include 'includes/header_candidate.inc';
    ?>
    <main>
        <h1>Apply for a job</h1>
        
        <form action="apply_job_process.php" method="post" enctype="multipart/form-data">
        
        <div>
            <label for="resume">Upload Your Resume (PDF only):</label>
            <input type="file" name="resume" accept=".pdf" required>
        </div>

        <input type="hidden" name="jobId" value="<?php echo $jobId; ?>">
        <input type="hidden" name="candidateId" value="<?php echo $candidateId; ?>">

        <div>
            <button type="submit" name="applyJob">Apply for Job</button>
        </div>
    </form>


    </main>
    <?php
        include 'includes/footer.inc';
    ?>
</body>
</html>
