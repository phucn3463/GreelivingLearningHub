<?php
session_start();
require_once("settings.php");

if(!isset($_SESSION['loggedIn'])){
    header('location:index.html');
}

$username = $_SESSION['username'];
$employerId = $_SESSION['employerId'];

$jobId = $_GET['jobId'];

$conn = @mysqli_connect($host, $user, $pwd, $sql_db) or die ("Unable to connect");

$jobQuery = "SELECT
            tbljob.id AS jobId,
            tbljob.title AS jobTitle,
            tbljobspecialization.title AS jobSpec,
            tbljob.description AS jobDesc,
            tbljob.workingFormat AS jobWorkFormat,
            tbljob.experienceLevel AS jobExp,
            tbljob.salaryFrom AS jobSalFr,
            tbljob.salaryTo AS jobSalTo,
            tbljob.deadline AS jobDead,
            tbljob.slot AS jobAvaSlot,
            COUNT(tbljobapplication.id) AS jobAppSlot,
            tblcompany.id AS comId,
            tblemployer.id AS empId
            FROM
            tbljob
            JOIN tbljobspecialization ON tbljob.specializationId = tbljobspecialization.id
            JOIN tbljobapplication ON tbljob.id = tbljobapplication.jobId
            JOIN tblemployer ON tbljob.employerId = tblemployer.id
	        JOIN tblcompany ON tblemployer.companyId = tblcompany.id
            WHERE
            tbljob.id = $jobId;";
$jobQueryResult = mysqli_query($conn, $jobQuery) or die('Job query failed');
$jobQueryRow = mysqli_fetch_assoc($jobQueryResult);

$companyQuery = "SELECT tblcompany.name AS comName,
            tblcompany.description AS comDesc,
            tblcompany.size AS comSize,
            tblcompany.email AS comEmail,
            tblcompany.phone AS comPhone,
            tbllocationward.full_name_en AS comLocationWard,
            tbllocationdistrict.full_name_en AS comLocationDistrict,
            tbllocationprovince.full_name_en AS comLocationProvince,
            tblcompany.locationDesc AS comLocationDetail
            FROM
            tblcompany
            JOIN tbllocationward ON tblcompany.locationId = tbllocationward.code
            JOIN tbllocationdistrict ON tbllocationward.district_code = tbllocationdistrict.code
            JOIN tbllocationprovince ON tbllocationdistrict.province_code = tbllocationprovince.code
            WHERE
            tblcompany.id = " . $jobQueryRow['comId'];
$companyQueryResult = mysqli_query($conn, $companyQuery) or die('Job query failed');
$companyQueryRow = mysqli_fetch_assoc($companyQueryResult);

$employerQuery = "SELECT tblemployer.firstName AS empFName,
            tblemployer.lastName AS empLName,
            tblemployer.email AS empEmail,
            tblemployer.phone AS empPhone
            FROM tblemployer
            WHERE tblemployer.id = " . $jobQueryRow['empId'];
$employerQueryResult = mysqli_query($conn, $employerQuery) or die('Job query failed');
$employerQueryRow = mysqli_fetch_assoc($employerQueryResult);

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
        include 'includes/header_employer.inc';
    ?>
    <main>
        <h1>Job's detail</h1>
        <h2>Title: <?php echo $jobQueryRow['jobTitle']; ?></h2>
        <p>Specialization: <?php echo $jobQueryRow['jobSpec']; ?></p>

        <div>
            <h2>About the company</h2>
            <p>Name: <?php echo $companyQueryRow['comName']; ?></p>
            <p>Description: <?php echo $companyQueryRow['comDesc']; ?></p>
            <p>Size: <?php echo $companyQueryRow['comSize']; ?></p>
            <p>Contact: </p>
            <ul>
                <li>Phone number: <a href="tel:<?php echo $companyQueryRow['comPhone']; ?>"><?php echo $companyQueryRow['comPhone']; ?></a></li>
                <li>Email: <a href="mailto:<?php echo $companyQueryRow['comEmail']; ?>"><?php echo $companyQueryRow['comEmail']; ?></a></li>
                <li>Location: <?php echo $companyQueryRow['comLocationDetail'] . ', ' . $companyQueryRow['comLocationWard'] . ', ' . $companyQueryRow['comLocationDistrict'] . ', ' . $companyQueryRow['comLocationProvince']; ?></li>
            </ul>
        </div>

        <div>
            <h2>About the job</h2>
            <p>Job description: <?php echo $jobQueryRow['jobDesc']; ?></p>
            <p>Working format: <?php echo $jobQueryRow['jobWorkFormat']; ?></p>
            <p>Experience level: <?php echo $jobQueryRow['jobExp']; ?></p>
            <p>Salary: from <?php echo $jobQueryRow['jobSalFr']; ?> to <?php echo $jobQueryRow['jobSalTo']; ?></p>
            <p>Deadline for apply: <?php echo $jobQueryRow['jobDead']; ?></p>
            <p>Slot available: <?php echo $jobQueryRow['jobAvaSlot']; ?></p>
        </div>

        <div>
            <h2>About the employer</h2>
            <p>Name: <?php echo $employerQueryRow['empFName'] . ' ' . $employerQueryRow['empLName']; ?></p>
            <p>Contact:</p>
            <ul>
                <li>Phone number: <a href="tel:<?php echo $employerQueryRow['empPhone']; ?>"><?php echo $employerQueryRow['empPhone']; ?></a></li>
                <li>Email: <a href="mailto:<?php echo $employerQueryRow['empEmail']; ?>"><?php echo $employerQueryRow['empEmail']; ?></a></li>
            </ul>
        </div>

        <div>
            <a href="view_applications.php?jobId=<?php echo $jobQueryRow['jobId']; ?>">View Applications</a>
            <a href="edit_job.php?jobId=<?php echo $jobQueryRow['jobId']; ?>">Edit details</a>
            <a href="delete_job.php?jobId=<?php echo $jobQueryRow['jobId']; ?>">Delete job</a>
        </div>

    </main>
    <?php
        include 'includes/footer.inc';
    ?>
</body>
</html>
