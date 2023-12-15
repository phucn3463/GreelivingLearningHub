<?php
session_start();

require_once("settings.php");
$conn = @mysqli_connect($host, $user, $pwd, $sql_db) or die ("Unable to connect");

$username = $_SESSION['username'];

$empQuery = "SELECT * FROM tbluser WHERE username = '$username'";
$empQueryResult = mysqli_query($conn, $empQuery) or die('Query failed');
$empRow = mysqli_fetch_assoc($empQueryResult);
$empId = $empRow['employerId'];

$_SESSION['employerId'] = $empId;

if(!isset($_SESSION['loggedIn'])){
    header('location:index.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <?php
        include 'includes/header_employer.inc';
    ?>
    <div class='userDashboard'>
        <?php
            $nameQuery = "SELECT firstname, lastname FROM tblemployer WHERE id = $empId";
            $nameQueryResult = mysqli_query($conn, $nameQuery) or die('Name query failed');
            $nameRow = mysqli_fetch_assoc($nameQueryResult)
        ?>
        <h1>Welcome, <?php echo $nameRow['firstname'] . ' ' . $nameRow['lastname']; ?>!</h1>
        <div>
            <h2>My Dashboard</h2>
            <div class='contents'>
                <?php 
                    $job_query = "SELECT 
                    tbljob.id,
                    tbljob.title,
                    tbljob.deadline,
                    tbljob.salaryFrom,
                    tbljob.salaryTo,
                    tbljobspecialization.title AS specialization,
                    tbljob.workingFormat,
                    tbljob.experienceLevel,
                    tbljob.slot,
                    COUNT(tbljobapplication.jobId) AS applicationCount
                FROM tbljob
                JOIN tbljobspecialization ON tbljob.specializationId = tbljobspecialization.id
                LEFT JOIN tbljobapplication ON tbljob.id = tbljobapplication.jobId
                WHERE tbljob.employerId = $empId
                GROUP BY 
                    tbljob.id,
                    tbljob.title,
                    tbljob.deadline,
                    tbljob.salaryFrom,
                    tbljob.salaryTo,
                    specialization,
                    tbljob.workingFormat,
                    tbljob.experienceLevel,
                    tbljob.slot;
                ";
                    $job_result = mysqli_query($conn, $job_query) or die('Job query failed');
                    
                    if (mysqli_num_rows($job_result) > 0) {
                        while ($job_row = mysqli_fetch_assoc($job_result)) {
                            echo "<div class='content'>"; 
                            $jobId = $job_row['id'];
                            echo "<p>" . $job_row['title'] . "</p>";
                            echo "<p>Deadline: " . $job_row['deadline'] . "</p>";
                            echo "<p>Salary: " . $job_row['salaryFrom'] . " - " . $job_row['salaryTo'] . "</p>";
                            echo "<p>Specialization: " . $job_row['specialization'] . "</p>";
                            echo "<p>Working Format: " . $job_row['workingFormat'] . "</p>";
                            echo "<p>Experience Level: " . $job_row['experienceLevel'] . "</p>";
                            $totalSlots = $job_row['applicationCount'] + $job_row['slot'];
                            echo "<p>Applied: "  . $job_row['applicationCount'] . " / " . $totalSlots . "</p>";
                            echo "<p><a href='job_detail_employer.php?jobId=$jobId'>View detail </a></p>";
                            echo '</div>';
                        }
                    } else {
                        echo "<div class='nocontent'><p>There is no job to display.</p></div>";
                    }
                ?>
            </div>
        </div>
    </div>
    <?php
        include 'includes/footer.inc';
    ?>
</body>
</html>