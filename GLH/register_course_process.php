<?php

session_start();
require_once("settings.php");
$conn = @mysqli_connect($host, $user, $pwd, $sql_db) or die ("Unable to connect");

if(!isset($_SESSION['loggedIn'])){
    header('location:index.html');
}

$username = $_SESSION['username'];
$candidateId = $_POST['candidateId'];
$courseId = $_POST['courseId'];

//Assume the payment is successfull
$paymentStatus = "Completed";
$transactionId = mt_rand(1000000000, 9999999999);

if ($paymentStatus == "Completed") {

    $courseRegistrationQuery = "INSERT INTO `tblcourseregistration`(`candidateId`, `courseId`, `status`) VALUES ('$candidateId', '$courseId', 'Studying')";
    $courseRegistrationQueryResult = mysqli_query($conn, $courseRegistrationQuery) or die('Query failed');

    if ($courseRegistrationQueryResult) {
        $getCourseRegistrationQuery = "SELECT id from tblcourseregistration WHERE candidateId = '$candidateId' AND courseId = '$courseId'";
        $getCourseRegistrationQueryResult = mysqli_query($conn, $getCourseRegistrationQuery) or die('Query failed');

        if ($getCourseRegistrationQueryResult) {
            $getCourseRegistrationQueryRow = mysqli_fetch_assoc($getCourseRegistrationQueryResult);
            $courseRegistrationPaymentQuery = "INSERT INTO `tblcourseregistrationpayment`(`courseRegistrationId`, `paymentStatus`, `transactionId`) VALUES ('" . $getCourseRegistrationQueryRow['id'] . "','$paymentStatus','$transactionId')";
            $courseRegistrationPaymentQueryResult = mysqli_query($conn, $courseRegistrationPaymentQuery) or die('Query failed');
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Registration</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <?php
        include 'includes/header_candidate.inc';
    ?>
    <main>
        <?php
            if ($courseRegistrationPaymentQueryResult) {

                $getCourseRegistrationPaymentQuery = "SELECT * FROM tblcourseregistrationpayment WHERE courseRegistrationId = '" . $getCourseRegistrationQueryRow['id'] . "'";
                $getCourseRegistrationPaymentResult = mysqli_query($conn, $getCourseRegistrationPaymentQuery) or die('Query failed');
                $getCourseRegistrationPaymentRow = mysqli_fetch_assoc($getCourseRegistrationPaymentResult);

                echo "<h1>Order Completed</h1>";
                echo "<div class='order-id'>Transaction ID: {$getCourseRegistrationPaymentRow['transactionId']}</div>";
                echo "<p>Thank you for your purchase! Your order has been processed successfully.</p>";
                echo "<a href='learning_course_detail.php?courseregistrationId=" . $getCourseRegistrationPaymentRow['courseRegistrationId'] . "'>View my course</a>";
            } else {
                echo "<h1>Order Failed</h1>";
                echo "<p>There was an error processing your order. Please try again.</p>";
            }
        ?>
    </main>
    <?php
        include 'includes/footer.inc';
    ?>
</body>
</html>
