<?php
session_start();
require_once("settings.php");

$conn = @mysqli_connect($host, $user, $pwd, $sql_db) or die("Unable to connect");

if (!isset($_SESSION['loggedIn'])) {
    header('location:index.html');
}

$jobApplicationId = $_GET['applicationId'];

$jobQuery = "SELECT jobId, status FROM `tbljobapplication` WHERE id = $jobApplicationId";
$jobQueryResult = mysqli_query($conn, $jobQuery);
$jobQueryDetail = mysqli_fetch_assoc($jobQueryResult);

// Check if the jobApplicationId and new status are set in the URL
if (isset($_GET['update_status'])) {

    $newStatus = $_GET['newStatus'];

    // Update the status in the database
    $updateStatusQuery = "UPDATE tbljobapplication SET status = '$newStatus' WHERE id = $jobApplicationId";
    $updateStatusResult = mysqli_query($conn, $updateStatusQuery);

    if ($updateStatusResult) {
        if ($newStatus == 'Awaiting Interview') {
            // Redirect to schedule.php if the new status is 'Awaiting Interview'
            header("location:change_appointment.php?jobApplicationId=" . $jobApplicationId);
        } else {
            // Redirect to view_applications.php for other statuses
            header("location:view_applications.php?jobId=" . $jobQueryDetail['jobId']);
        }
        exit();
    } else {
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
    <title>Change Application Status</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <?php
        include 'includes/header_employer.inc';
    ?>

    <main>
    <div class="change-status-section">
        <h1>Change Application Status</h1>

        <form method="get" action="change_status.php" class="form-change-status">
            <input type="hidden" name="applicationId" value="<?php echo $jobApplicationId; ?>">
            <div class="form-change-status-section">
                <div class="form-change-status-element">
                    <label for="newStatus">New Status:</label>
                    <select name="newStatus" id="newStatus" required>
                        <option value="Awaiting Interview" <?php echo ($jobQueryDetail['status'] === 'Awaiting Interview') ? 'selected' : ''; ?>>Awaiting Interview</option>
                        <option value="Accepted" <?php echo ($jobQueryDetail['status'] === 'Accepted') ? 'selected' : ''; ?>>Accepted</option>
                        <option value="Rejected" <?php echo ($jobQueryDetail['status'] === 'Rejected') ? 'selected' : ''; ?>>Rejected</option>
                    </select>
                </div>
                <input type="submit" value="Update status" name="update_status" class="form-change-status-button"/>
            </div>
        </form>
    </div>

    </main>

    <?php
        include 'includes/footer.inc';
    ?>
</body>
</html>

<?php
    mysqli_close($conn);
?>