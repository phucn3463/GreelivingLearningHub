<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Greeliving Learning Hub</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <?php
        include 'includes/header.inc';
    ?>
    <?php
    // Check if there's an error parameter in the URL
    if (isset($_GET['error'])) {
        $error = $_GET['error'];
        switch ($error) {
            case 'missing_info':
                echo '<div>Please fill in all the blanks.</div>';
                break;
            case 'unmatched_passwords':
                echo '<div>Passwords do not match. Please try again.</div>';
                break;
            case 'no_role':
                echo '<div>Please choose your role.</div>';
                break;
            case 'database_error':
                echo '<div>Database error. Please try again later.</div>';
                break;
            case 'invalid_login':
                echo '<div>Wrong username or password. Please try again!</div>';
                break;
            default:
                echo '<div>An unexpected error occurred.</div>';
                break;
        }
    }
    if (isset($_GET['registration']) && $_GET['registration'] === 'success') {
        echo '<div>Registration successful. You can now log in.</div>';
    }
    ?>
    <div class="vu00">
        <div class="vu1" id="vu15">
            <div class="vu2">
                <form action="register.php" method="post" class="vu3">
                    <h1 class="vu4">Create Account</h1>
                    <div class="vu7">
                        <input type="text" placeholder="Username" name="username"/>
                    </div>
                    <div class="vu7">
                        <input type="password" placeholder="Password" name="password"/>
                    </div>
                    <div class="vu7">
                        <input type="password" placeholder="Confirm Password" name="cpassword"/>
                    </div>
                    <div class="vu0">
                        <p>Who are you?</p>
                        <input type="radio" name="role" value="Candidate" class="vu17" id = "candidate" ><label for="candidate">Candidate</label>
                        <input type="radio" name="role" value="Employer" class="vu16" id = "employer" ><label for="employer">Employer</label>
                    </div>  
                    <input type="submit" name="register" value="Sign Up" class="vu18">
                </form>
            </div>
            <div class="vu8">
                <form action="login.php" method="post" class="vu3">
                    <h1 class="vu4">Sign in</h1>
                    <div class="vu7">
                        <input type="text" placeholder="Username" name="username" required=""/>
                    </div>
                    <div class="vu7">
                        <input type="password" placeholder="Password" name="password" required=""/>
                    </div>
                    <input type="submit" name="login" value="Sign In" class="vu18" />
                </form>
            </div>
            <div class="vu9">
                <div class="vu10">
                    <div class="vu11">
                        <h1>Welcome!</h1>
                        <p>To keep connected with us please login with your personal info</p>
                        <button id="vu12">Sign In</button>
                    </div>
                    <div class="vu13">
                        <h1>Hello, Friend!</h1>
                        <p>Enter your personal details and start journey with us</p>
                        <button id="vu14">Sign Up</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
        include 'includes/footer.inc';
    ?>

    <script type="text/javascript" src="js/welcome.js"></script>
</body>
</html>