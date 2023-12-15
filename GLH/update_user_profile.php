<?php
session_start();
require_once("settings.php");
$conn = @mysqli_connect($host, $user, $pwd, $sql_db) or die("Unable to connect");

$username = $_SESSION['username'];

if (isset($_POST['update_profile'])) {
    $role = $_POST['role'];
} else {
    $role = $_GET['role'];
}

if ($role == 'candidate') {
    $candidateId = $_SESSION['candidateId'];
    $select = mysqli_query($conn, "SELECT * FROM tblcandidate WHERE id = $candidateId") or die('query failed');
} else if ($role == 'employer') {
    $employerId = $_SESSION['employerId'];
    $select = mysqli_query($conn, "SELECT * FROM tblemployer WHERE id = $employerId") or die('query failed');
}

$selectuser = mysqli_query($conn, "SELECT * FROM tbluser WHERE username = '$username'");
$row = mysqli_fetch_array($selectuser);

if (!isset($_SESSION['loggedIn'])) {
    header('location:index.php');
}

if (isset($_POST['update_profile'])) {
    $update_fname = mysqli_real_escape_string($conn, $_POST['update_fname']);
    $update_lname = mysqli_real_escape_string($conn, $_POST['update_lname']);
    $update_sex = mysqli_real_escape_string($conn, $_POST['update_sex']);
    $update_email = mysqli_real_escape_string($conn, $_POST['update_email']);
    $update_phone = mysqli_real_escape_string($conn, $_POST['update_phone']);

    if ($role == 'employer') {
        mysqli_query($conn, "UPDATE tblemployer SET firstName = '$update_fname', lastName = '$update_lname', sex = '$update_sex', email = '$update_email', phone = '$update_phone' WHERE id = '$employerId'") or die('query failed');
        header("Location: user.php");
    } elseif ($role == 'candidate') {
        mysqli_query($conn, "UPDATE tblcandidate SET firstName = '$update_fname', lastName = '$update_lname', sex = '$update_sex', email = '$update_email', phone = '$update_phone' WHERE id = '$candidateId'") or die('query failed');
        header("Location: user.php");
    } else {
        header("Location: update_user_profile.php?error=Update failed");
    }

    // Close the database connection
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="styles/style.css">
</head>

<body>
    <div class="update-profile">
        <?php
        if ($row['role'] === 'employer') {
            include 'includes/header_employer.inc';
        } elseif ($row['role'] === 'candidate') {
            include 'includes/header_candidate.inc';
        }
        ?>

        <h1>Update User Profile</h1>

        <?php
        if (isset($_GET['error'])) {
            echo '<div><p>Error: ' . $_GET['error'] . '</p></div>';
        }
        ?>

        <?php
        if (mysqli_num_rows($select) > 0) {
            $fetch = mysqli_fetch_array($select);
        }
        ?>

        <form action="update_user_profile.php" method="post" class="form-update-profile">
            <div class="form-update-profile-section">
                <div class="form-update-profile-element">
                    <span>First Name :</span>
                    <input type="text" name="update_fname" value="<?php echo $fetch['firstName'] ?>">
                </div>
                <div class="form-update-profile-element">
                    <span>Last Name :</span>
                    <input type="text" name="update_lname" value="<?php echo $fetch['lastName'] ?>">
                </div>
                <div class="form-update-profile-element">
                    <span>Sex :</span>
                    <select name="update_sex">
                        <option value="M" <?php if ($fetch['sex'] == 'M') echo 'selected'; ?>>Male</option>
                        <option value="F" <?php if ($fetch['sex'] == 'F') echo 'selected'; ?>>Female</option>
                    </select>
                </div>
                <div class="form-update-profile-element">
                    <span>Email :</span>
                    <input type="email" name="update_email" value="<?php echo $fetch['email'] ?>">
                </div>
                <div class="form-update-profile-element">
                    <span>Phone Number :</span>
                    <input type="number" name="update_phone" value="<?php echo $fetch['phone'] ?>">
                </div>
                <input type="hidden" value="<?php echo $role; ?>" name="role">
                <input type="submit" value="Update Profile" name="update_profile" class="form-update-profile-button"/>
            </div>    
        </form>
    </div>
    
    <?php
    include 'includes/footer.inc';
    ?>
</body>

</html>
