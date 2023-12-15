<?php
session_start();

require_once("settings.php");
$conn = @mysqli_connect($host, $user, $pwd, $sql_db) or die("Unable to connect");

if (!isset($_SESSION['loggedIn'])) {
    header('location:index.php');
}

$canId = $_SESSION['candidateId'];

// Check if the experience ID is provided in the URL
if (isset($_GET['id'])) {
    $experienceId = $_GET['id'];

    // Fetch the existing experience details
    $fetchExperienceQuery = "SELECT * FROM tblcandidateexperience WHERE id = $experienceId AND candidateId = $canId";
    $fetchExperienceResult = mysqli_query($conn, $fetchExperienceQuery);

    if (mysqli_num_rows($fetchExperienceResult) == 1) {
        $experience = mysqli_fetch_assoc($fetchExperienceResult);
    } else {
        echo "Experience not found.";
        exit();
    }
} else {
    echo "Experience ID not provided.";
    exit();
}

// Check if the form for updating the experience is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize the input (you may want to add more validation)
    $dateStart = mysqli_real_escape_string($conn, $_POST['dateStart']);
    $dateEnd = mysqli_real_escape_string($conn, $_POST['dateEnd']);
    $organization = mysqli_real_escape_string($conn, $_POST['organization']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);

    // Update the experience in the database
    $updateExperienceQuery = "UPDATE tblcandidateexperience SET dateStart = '$dateStart', dateEnd = '$dateEnd', organization = '$organization', role = '$role' WHERE id = $experienceId AND candidateId = $canId";

    if (mysqli_query($conn, $updateExperienceQuery)) {
        // Redirect to the page showing all experiences
        header("location: online_cv.php?candidateId=' . $canId . '");
        exit();
    } else {
        echo "Error updating experience: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Experience</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="styles/style.css">
</head>

<body>
    <?php include 'includes/header_candidate.inc'; ?>

    <main class='editexp'>
        <h1>Edit Experience</h1>

        <form method="post" action="">
            <div>
                <label for="dateStart">Date Start:</label>
                <input type="date" name="dateStart" value="<?php echo $experience['dateStart']; ?>" required>
            </div>
            <div>
                <label for="dateEnd">Date End:</label>
                <input type="date" name="dateEnd" value="<?php echo $experience['dateEnd']; ?>" required>
            </div>
            <div>
                <label for="organization">Organization:</label>
                <input type="text" name="organization" value="<?php echo $experience['organization']; ?>" required>
            </div>
            <div>
                <label for="role">Role:</label>
                <input type="text" name="role" value="<?php echo $experience['role']; ?>" required>
            </div>
            <div>
                <button type="submit">Update Experience</button>
            </div>
        </form>
    </main>

    <?php include 'includes/footer.inc'; ?>

</body>

</html>
