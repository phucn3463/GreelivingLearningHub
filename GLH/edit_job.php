<?php
session_start();
require_once("settings.php");

if (!isset($_SESSION['loggedIn'])) {
    header('location:index.html');
}

$conn = @mysqli_connect($host, $user, $pwd, $sql_db) or die("Unable to connect");

$jobId = $_GET['jobId'];

$currentJobQuery = "SELECT * FROM tbljob WHERE id = $jobId";
$currentJobQueryResult = mysqli_query($conn, $currentJobQuery);
$currentJobQueryRow = mysqli_fetch_assoc($currentJobQueryResult);

$currentJobNumberQuery = "SELECT jobId, COUNT(id) AS application_count FROM tbljobapplication WHERE jobId = $jobId;";
$currentJobNumberQueryResult = mysqli_query($conn, $currentJobNumberQuery);
$currentJobNumberQueryRow = mysqli_fetch_assoc($currentJobNumberQueryResult);

if (isset($_POST['update_job'])) {
    $newTitle = mysqli_real_escape_string($conn, $_POST['newTitle']);
    $newDescription = mysqli_real_escape_string($conn, $_POST['newDescription']);
    $newDeadline = mysqli_real_escape_string($conn, $_POST['newDeadline']);
    $newSalaryFrom = mysqli_real_escape_string($conn, $_POST['newSalaryFrom']);
    $newSalaryTo = mysqli_real_escape_string($conn, $_POST['newSalaryTo']);
    $newSpecializationId = mysqli_real_escape_string($conn, $_POST['newSpecializationId']);
    $newWorkingFormat = mysqli_real_escape_string($conn, $_POST['newWorkingFormat']);
    $newExpLevel = mysqli_real_escape_string($conn, $_POST['newExpLevel']);
    $newSlot = mysqli_real_escape_string($conn, $_POST['newSlot']);

    $newSlot = $newSlot - $currentJobNumberQueryRow['application_count'];

    // Insert the new job into the database
    $updateJobQuery = "UPDATE `tbljob` SET `title`='$newTitle',`description`='$newDescription',`deadline`='$newDeadline',`salaryFrom`='$newSalaryFrom',`salaryTo`='$newSalaryTo',`specializationId`='$newSpecializationId',`workingFormat`='$newWorkingFormat',`experienceLevel`='$newExpLevel',`slot`='$newSlot' WHERE id = $jobId";
    $updateJobQueryResult = mysqli_query($conn, $updateJobQuery);

    if ($updateJobQueryResult) {
        // Job creation successful, redirect to a success page or a relevant location
        header("location:job_detail_employer.php?jobId=$jobId");
        exit();
    } else {
        // Job creation failed, redirect to an error page or handle accordingly
        header('location:error_page.php');
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update job's information</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <?php
        include 'includes/header.inc';
    ?>

    <main>
        <h1>Update job's information</h1>
        <form method="post" action="edit_job.php?jobId=<?php echo $jobId; ?>">
            <div>
                <div>
                    <span>Title:</span>
                    <input type="text" name="newTitle" value="<?php echo $currentJobQueryRow['title']; ?>" required>
                </div>
                <div>
                    <span>Job description:</span><br/>
                    <textarea name="newDescription" cols="30" rows="10" required><?php echo $currentJobQueryRow['description']; ?></textarea>
                </div>
                <div>
                    <span>Deadline for apply:</span>
                    <input type="date" name="newDeadline" value="<?php echo $currentJobQueryRow['deadline']; ?>" required>
                </div>
                <div>
                    <span>Salary:</span>
                    <input type="number" name="newSalaryFrom" value="<?php echo $currentJobQueryRow['salaryFrom']; ?>" required>
                    <span> - </span>
                    <input type="number" name="newSalaryTo" value="<?php echo $currentJobQueryRow['salaryTo']; ?>" required>
                </div>
                <div>
                    <span>Specialization:</span>
                    <select name="newSpecializationId">
                        <?php
                            $specializationQuery = "SELECT id, title FROM tbljobspecialization";
                            $specializationResult = mysqli_query($conn, $specializationQuery);

                            if ($specializationResult) {
                                while ($specialization = mysqli_fetch_assoc($specializationResult)) {
                                    $selected = ($currentJobQueryRow['specializationId'] == $specialization['id']) ? 'selected' : '';
                                    echo '<option value="' . $specialization['id'] . '" ' . $selected . '>' . $specialization['title'] . '</option>';
                                }
                            } else {
                                echo '<option value="" disabled selected>No Specializations available</option>';
                            }
                        ?>
                    </select>
                </div>
                <div>
                    <span>Working Format:</span>
                    <select name="newWorkingFormat">
                        <option value="Remote" <?php echo ($currentJobQueryRow['workingFormat'] == 'Remote') ? 'selected' : ''; ?>>Remote</option>
                        <option value="Hybrid" <?php echo ($currentJobQueryRow['workingFormat'] == 'Hybrid') ? 'selected' : ''; ?>>Hybrid</option>
                        <option value="On-site" <?php echo ($currentJobQueryRow['workingFormat'] == 'On-site') ? 'selected' : ''; ?>>On-site</option>
                    </select>
                </div>
                <div>
                    <span>Experience Level:</span>
                    <select name="newExpLevel">
                        <option value="Internship" <?php echo ($currentJobQueryRow['experienceLevel'] == 'Internship') ? 'selected' : ''; ?>>Internship</option>
                        <option value="Entry-level" <?php echo ($currentJobQueryRow['experienceLevel'] == 'Entry-level') ? 'selected' : ''; ?>>Entry-level</option>
                        <option value="Junior" <?php echo ($currentJobQueryRow['experienceLevel'] == 'Junior') ? 'selected' : ''; ?>>Junior</option>
                        <option value="Mid-level" <?php echo ($currentJobQueryRow['experienceLevel'] == 'Mid-level') ? 'selected' : ''; ?>>Mid-level</option>
                        <option value="Senior" <?php echo ($currentJobQueryRow['experienceLevel'] == 'Senior') ? 'selected' : ''; ?>>Senior</option>
                    </select>
                </div>
                <div>
                    <span>Slot: </span>
                    <?php
                        $currentJobQueryRow['slot'] = $currentJobQueryRow['slot'] + $currentJobNumberQueryRow['application_count'];
                    ?>
                    <input type="number" name="newSlot" value="<?php echo $currentJobQueryRow['slot']; ?>" required>
                </div>
                <input type="submit" value="Update job" name="update_job"/>
            </div>    
        </form>
    </main>

    <?php
        include 'includes/footer.inc';
    ?>
</body>
</html>

<?php
    mysqli_close($conn);
?>