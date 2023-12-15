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
        <div class="apply-job">
            <h1>Apply for a job</h1>
            <form action="apply_job_process.php" method="post" enctype="multipart/form-data" class="form-apply-job">
                <div class="form-apply-job-section">
                    <div class="form-apply-job-element">
                        <label for="resume">Upload Your Resume (PDF only):</label>
                        <input type="file" name="resume" accept=".pdf" required>
                    </div>
                    <input type="hidden" name="jobId" value="<?php echo $jobId; ?>">
                    <input type="hidden" name="candidateId" value="<?php echo $candidateId; ?>">
                    <button type="submit" name="applyJob" class="form-apply-job-button">Apply For Job</button>
                </div>    
            </form>
        </div>
        
    </main>
    <?php
        include 'includes/footer.inc';
    ?>
</body>
</html>
