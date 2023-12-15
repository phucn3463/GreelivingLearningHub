<?php
    session_start();

    require_once("settings.php");
    $conn = @mysqli_connect($host, $user, $pwd, $sql_db) or die ("Unable to connect");

    if (!isset($_SESSION['loggedIn'])) {
        header('location:index.html');
    }

    $username = $_SESSION['username'];

    $select = mysqli_query($conn, "SELECT * FROM tbluser WHERE username = '$username'");
    $row = mysqli_fetch_array($select);
    
    if(is_array($row)) {
        switch ($row['role']) {
            case 'employer':
                $link = "employer.php";
                break;
            case 'candidate':
                $link = "candidate.php";
                break;
            default:
                header("Location: index.php?error=invalid_login"); 
                break;
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <?php
        include 'includes/header.inc';
    ?>

    <main>
        <p>Oops! Something went wrong. The requested resource could not be found or an error occurred.</p>
        <p>Please go back to the <a href= '<?php echo $link; ?>' >homepage</a> or try again later.</p>
    </main>

    <?php
        include 'includes/footer.inc';
    ?>
</body>
</html>
