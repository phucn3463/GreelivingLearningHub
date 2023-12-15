<?php
session_start();
require_once("settings.php");
$username = $_SESSION['username'];
$conn = @mysqli_connect($host, $user, $pwd, $sql_db) or die ("Unable to connect");

$selectuser = mysqli_query($conn, "SELECT role FROM tbluser WHERE username = '$username'");
$row = mysqli_fetch_array($selectuser);

if(!isset($_SESSION['loggedIn'])){
    header('location:index.html');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
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
    <div class="user-section">
        <i class="fa-solid fa-user"></i>
        <a href="update_user_profile.php?role=<?php echo $row['role']; ?>"><button class="user-section1">UPDATE PROFILE</button></a>
        <a href="change_password.php"><button class="user-section2">CHANGE PASSWORD</button></a>
        <a href="logout.php"><button class="user-section3">SIGN OUT</button></a>
    </div>
    <?php 
        include 'includes/footer.inc';
    ?>
</body>
</html>