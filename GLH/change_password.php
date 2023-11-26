<?php
session_start();
require_once("settings.php");
$username = $_SESSION['username'];
$conn = @mysqli_connect($host, $user, $pwd, $sql_db) or die("Unable to connect");

$selectuser = mysqli_query($conn, "SELECT * FROM tbluser WHERE username = '$username'");
$row = mysqli_fetch_array($selectuser);

if (!isset($_SESSION['loggedIn'])) {
    header('location:index.php');
}

$selectpassword = mysqli_query($conn, "SELECT password FROM tbluser WHERE username = '$username'") or die('query failed');
$fetchpassword = mysqli_fetch_assoc($selectpassword);

if (isset($_POST['update_pass'])) {
    $old_password = mysqli_real_escape_string($conn, $_POST['old_password']);
    $update_password = mysqli_real_escape_string($conn, $_POST['update_password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    // Check if old password matches
    if ($old_password == $fetchpassword['password']) {
        // Check if new password matches the confirmed password
        if ($update_password != $confirm_password) {
            // New password and confirm password do not match
            header("Location: change_password.php?error=Unmatched password");
        } else {
            mysqli_query($conn, "UPDATE tbluser SET password = '$update_password' WHERE username = '$username'") or die('query failed');
            header("Location: user.php");
            // Password updated successfully
        }
    } else {
        // Old password is incorrect
        header("Location: change_password.php?error=Incorrect old password");
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <?php 
        if ($row['role'] === 'employer') {
            include 'includes/header_employer.inc';
        } elseif ($row['role'] === 'candidate') {
            include 'includes/header_candidate.inc';
        }
    ?>
        <h1>Change Password</h1>

        <?php
            if (isset($_GET['error']))
            {
                echo '<div><p>Error: ' . $_GET['error'] . '</p></div>';
            }
        ?>

 
        <form action="" method="post">
            <div>
                <div>
                    <label for="old_password">Old Password: </label>
                    <input type="password" name="old_password" id="old_password" required>
                </div>
                <div>
                    <label for="update_password">New Password: </label>
                    <input type="password" name="update_password" id="update_password" required>
                </div>
                <div>
                    <label for="confirm_password">Confirm Password: </label>
                    <input type="password" name="confirm_password" id="confirm_password" required>
                </div>
                <input type="submit" value="Update password" name="update_pass"/>
            </div>    
        </form>
    <?php 
        include 'includes/footer.inc';
    ?>
</body>
</html>