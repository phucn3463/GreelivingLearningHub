<?php
session_start();
require_once("settings.php");
$conn = @mysqli_connect($host, $user, $pwd, $sql_db) or die ("Unable to connect");

if(!isset($_SESSION['loggedIn'])){
    header('location:index.html');
}

$username = $_SESSION['username'];
$candidateId = $_SESSION['candidateId'];
$courseId = $_GET['courseId'];

$getPriceQuery = "SELECT price FROM tblcourse WHERE id = $courseId";
$getPriceQueryResult = mysqli_query($conn, $getPriceQuery) or die('Query failed');
$getPriceQueryRow = mysqli_fetch_assoc($getPriceQueryResult);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register for a course</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="styles/style.css">
    <script src="js/jquery-1.9.1.min.js"></script>
    <script src="js/payment.js"></script>
</head>
<body>
    <?php
        include 'includes/header_candidate.inc';
    ?>
    <main>
        <div class="register-course">
            <h1>Register for a course</h1>
            <div>   
                
                <form action="register_course_process.php" method="post" id="paymentform" class="form-register-course">
                    <div class="form-register-course-section">
                        <p>Course price: <?php echo $getPriceQueryRow['price']; ?></p>  
                        <div class="form-register-course-element1">
                            <label id="onlinePaymentMethodLabel">Choose your payment option:</label></br>
                            <input id="visa" type="radio" name="onlinePaymentMethod" value="visa"><label id="visaLabel" for="visa">Visa</label>
                            <input id="masterCard" type="radio" name="onlinePaymentMethod" value="masterCard"><label id="masterCardLabel" for="masterCard">MasterCard</label>
                            <input id="americanExpress" type="radio" name="onlinePaymentMethod" value="americanExpress"><label id="americanExpressLabel" for="americanExpress">American Express</label>
                        </div>
                        <div class="form-register-course-element">
                            <label for="name">Card Owner: </label>
                            <input id="name" type="text" name="name">
                        </div>
                        <div class="form-register-course-element">
                            <label for="cardNumber">Card Number: </label>
                            <input id="cardNumber" type="number" name="cardNumber">
                        </div>
                        <div class="form-register-course-element">
                            <label for="cvvNumber">CVV: </label>
                            <input id="cvvNumber" type="number" name="cvvNumber">
                        </div>

                        <input type="hidden" name="courseId" value="<?php echo $courseId; ?>">
                        <input type="hidden" name="candidateId" value="<?php echo $candidateId; ?>">
                        <button type="submit" name="registerCourse" class="form-register-course-button">Register</button>
                    </div>  
                </form>
            </div>
        </div>

    </main>
    <?php
        include 'includes/footer.inc';
    ?>
</body>
</html>
