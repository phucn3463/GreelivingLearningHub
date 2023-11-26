<?php
session_start();

$username = $_POST['username'];
$password = $_POST['password'];
$cpassword = $_POST['cpassword'];
$role = $_POST['role'];

$_SESSION['user_role'] = $role;
$_SESSION['user_name'] = $username;

require_once("settings.php");
$conn = @mysqli_connect($host, $user, $pwd, $sql_db) or die("Unable to connect");

if (isset($_POST['register'])) {

    if ($password != $cpassword) {
        header("Location: index.php?error=unmatched_passwords");
        exit(); // Stop further execution to prevent unintended redirects
    } elseif ($role == '') {
        header("Location: index.php?error=no_role");
        exit();
    } elseif ($username == '' || $password == '' || $cpassword == '') {
        header("Location: index.php?error=missing_info");
        exit();
    } else {
        $query = mysqli_query($conn, "INSERT INTO tbluser (username, password, role) VALUES ('$username', '$password', '$role')");

        if ($query) {
            header("Location: register_profile.php");
            exit();
        } else {
            header("Location: index.php?error=database_error");
            exit();
        }
    }
} else {
    header("Location: index.php");
    exit();
}
?>
