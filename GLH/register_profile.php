<?php
session_start();
require_once("settings.php");
$conn = @mysqli_connect($host, $user, $pwd, $sql_db) or die ("Unable to connect");

if(isset($_POST['create_profile'])) {
    $register_firstname = mysqli_real_escape_string($conn, $_POST['register_firstname']);
    $register_lastname = mysqli_real_escape_string($conn, $_POST['register_lastname']);
    $register_birthdate = mysqli_real_escape_string($conn, $_POST['register_birthdate']);
    $register_sex = mysqli_real_escape_string($conn, $_POST['register_sex']);
    $register_email = mysqli_real_escape_string($conn, $_POST['register_email']);
    $register_phonenumber = mysqli_real_escape_string($conn, $_POST['register_phonenumber']);
    $register_company = mysqli_real_escape_string($conn, $_POST['register_company']);

    switch ($_SESSION['user_role']) {
        case 'Employer':
            mysqli_query($conn, "INSERT INTO tblemployer (firstName, lastName, birthdate, sex, email, phone, companyId) VALUES ('$register_firstname', '$register_lastname', '$register_birthdate', '$register_sex', '$register_email', '$register_phonenumber', '$register_company')");
            
            // Update employer id to user table
            $empIdQuery = mysqli_query($conn, "SELECT id FROM tblemployer WHERE email = '$register_email'");
            $emp = mysqli_fetch_assoc($empIdQuery);
            $empId = $emp['id'];
            mysqli_query($conn, "UPDATE tbluser SET employerId = $empId WHERE username = '" . $_SESSION['user_name'] . "'");
            
            // Direct
            header("Location: index.php?registration=success");
            break;
    
        case 'Candidate':
            mysqli_query($conn, "INSERT INTO tblcandidate (firstName, lastName, birthdate, sex, email, phone) VALUES ('$register_firstname', '$register_lastname', '$register_birthdate', '$register_sex', '$register_email', '$register_phonenumber')");
            
            // Update candidate id to user table
            $canIdQuery = mysqli_query($conn, "SELECT id FROM tblcandidate WHERE email = '$register_email'");
            $can = mysqli_fetch_assoc($canIdQuery);
            $canId = $can['id'];
            mysqli_query($conn, "UPDATE tbluser SET candidateId = $canId WHERE username = '" . $_SESSION['user_name'] . "'");
            
            // Direct
            header("Location: index.php?registration=success");
            break;
    
        default:
            header("Location: register_profile.php?failedtocreateprofile");
            break;
    }
    
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <?php
        include 'includes/header.inc';
    ?>
    <div>
        <h3>Create Profile</h3>
        <form action="" method="post">
            <div>
                <div>
                    <span>First Name :</span>
                    <input type="text" name="register_firstname" value="" required>
                </div>
                <div>
                    <span>Last Name :</span>
                    <input type="text" name="register_lastname" value="" required>
                </div>
                <div>
                    <span>Birthdate :</span>
                    <input type="date" name="register_birthdate" value="" required>
                </div>
                <div>
                    <span>Sex :</span>
                    <select name="register_sex">
                        <option value="M" required>Male</option>
                        <option value="F">Female</option>
                    </select>
                </div>
                <div>
                    <span>Email :</span>
                    <input type="email" name="register_email" value="" required>
                </div>
                <div>
                    <span>Phone Number :</span>
                    <input type="number" name="register_phonenumber" value="" required>
                </div>
                <?php
                    if ($_SESSION['user_role'] === 'Employer') 
                    {
                        echo ('<div><span>Company :</span> <select name="register_company">');
                        
                        $companyQuery = mysqli_query($conn, "SELECT id, name FROM tblcompany");

                        while ($company = mysqli_fetch_assoc($companyQuery)) 
                        {
                            echo '<option value="' . $company['id'] . '">' . $company['name'] . '</option>';
                        }
                        echo '</select>';
                    }


                ?>
                <input type="submit" value="Create profile" name="create_profile"/>
            </div>    
        </form>
    </div>
    <?php
        include 'includes/footer.inc';
    ?>
</body>
</html>