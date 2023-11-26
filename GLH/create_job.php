<?php
session_start();
require_once("settings.php");
$conn = @mysqli_connect($host, $user, $pwd, $sql_db) or die ("Unable to connect");

if(!isset($_SESSION['loggedIn'])){
    header('location:index.html');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Creation</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <?php
        include 'includes/header_employer.inc';
    ?>
    <div class="vu44">
        <h3 class="vu29">Create a Job</h3>
        <form action="create_job_process.php" method="post">
            <div>
                <div>
                    <span>Title:</span>
                    <input type="text" name="newJob_title" value="" required = "">
                </div>
                <div>
                    <span>Job description:</span><br/>
                    <textarea name="newJob_description" cols="30" rows="10"></textarea>
                </div>
                <div>
                    <span>Deadline for apply:</span>
                    <input type="date" name="newJob_deadline" value="" required = "">
                </div>
                <div>
                    <span>Salary:</span>
                    <input type="number" name="newJob_salaryFrom" value="" required = "">
                    <span> - </span>
                    <input type="number" name="newJob_salaryTo" value="" required = "">
                </div>
                <div>
                    <span>Specialization:</span>
                    <select name="newJob_specializationId">
                        <?php
                            $specializationQuery = "SELECT id, title FROM tbljobspecialization";
                            $specializationResult = mysqli_query($conn, $specializationQuery);

                            if ($specializationResult) {
                                while ($specialization = mysqli_fetch_assoc($specializationResult)) {
                                    echo '<option value="' . $specialization['id'] . '">' . $specialization['title'] . '</option>';
                                }
                            } else {
                                echo '<option value="" disabled selected>No Specializations available</option>';
                            }
                        ?>
                    </select>
                </div>
                <div>
                    <span>Working Format:</span>
                    <select name="newJob_workingFormat">
                        <option value="Remote">Remote</option>
                        <option value="Hybrid">Hybrid</option>
                        <option value="On-site">On-site</option>
                    </select>
                </div>
                <div>
                    <span>Experience Level :</span>
                    <select name="newJob_expLevel">
                        <option value="Internship">Internship</option>
                        <option value="Entry-level">Entry-level</option>
                        <option value="Junior">Junior</option>
                        <option value="Mid-level">Mid-level</option>
                        <option value="Senior">Senior</option>
                    </select>
                </div>
                <div>
                    <span>Slot: </span>
                    <input type="number" name="newJob_slot" value="">
                </div>
                <input type="submit" value="Create new job" name="create_job"/>
            </div>    
        </form>
    </div>
    <?php
        include 'includes/footer.inc';
    ?>
</body>
</html>