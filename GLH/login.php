<?php
session_start();

require_once("settings.php");
$conn = @mysqli_connect($host, $user, $pwd, $sql_db) or die ("Unable to connect");

if (isset($_POST['login']))
    $username = $_POST['username'];
    $password = $_POST['password'];

    $select = mysqli_query($conn, "SELECT * FROM tbluser WHERE username = '$username' AND password = '$password'");
    $row = mysqli_fetch_array($select);
    
    if(is_array($row)) {
        $_SESSION['username'] = $row['username'];
        $_SESSION['loggedIn'] = 1;

        switch ($row['role']) {
            case 'employer':
                header("Location: employer.php");
                break;
            case 'candidate':
                header("Location: candidate.php");
                break;
            default:
                header("Location: index.php?error=invalid_login");
                break;
        }
        
    } else {
        header("Location: index.php?error=invalid_login");
    }

?>