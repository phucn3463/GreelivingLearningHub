<?php
    // Create a connection to the database
    require_once("settings.php");
    $conn = @mysqli_connect($host, $user, $pwd, $sql_db);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search for jobs</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <?php
        include 'includes/header_candidate.inc';
    ?>

    <main class='candidatejobsearch'>
        <h1>Search for jobs</h1>

        <!-- <div class='formfilter'>
            forms here to filter
        </div> -->

        <?php
            $results_per_page = 12;
            if (!isset($_GET['page'])) {
                $page = 1;
            } else {
                $page = $_GET['page'];
            }
            $start_from = ($page - 1) * $results_per_page;

            // Query to get total number of rows
            $count_query = "SELECT COUNT(*) as total FROM `tbljob`";
            $count_result = @mysqli_query($conn, $count_query);
            $count_row = mysqli_fetch_assoc($count_result);
            $total_pages = ceil($count_row['total'] / $results_per_page);

            // Query to fetch job data with LIMIT
            $query = "SELECT tbljob.id, tbljob.title, tblemployer.firstname, tblemployer.lastname, tblcompany.name, tblcompany.description, tbljob.deadline, tbljob.salaryFrom, tbljob.salaryTo, tbljobspecialization.title AS jobspecialization, tbljob.workingFormat, tbljob.experienceLevel, tbljob.slot FROM `tbljob` JOIN tblemployer ON tbljob.employerId = tblemployer.id JOIN tbljobspecialization ON tbljob.specializationId = tbljobspecialization.id JOIN tblcompany ON tblemployer.companyId = tblcompany.id LIMIT $start_from, $results_per_page";
            $result = @mysqli_query($conn, $query);

            echo "<div class='listpane'>";

            if ($result && mysqli_num_rows($result) > 0) {
                // Loop through the rows and display the data in rectangles
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<div class='listcontent'>";
                    echo '<h3>Job title: ' . $row['title'] . '</h3>';
                    echo '<p>Company: ' . $row['name'] . ' - ' . $row['description'] . '</p>';
                    echo '<p>Employer: ' . $row['firstname'] . ' ' . $row['lastname'] . '</p>';
                    echo '<p>Deadline: ' . $row['deadline'] . '</p>';
                    echo '<p>Salary: from ' . $row['salaryFrom'] . ' to ' . $row['salaryTo'] . '</p>';
                    echo '<p>Job specialization: ' . $row['jobspecialization'] . '</p>';
                    echo '<p>Working Format: ' . $row['workingFormat'] . '</p>';
                    echo '<p>Working experience requirement: ' . $row['experienceLevel'] . '</p>';
                    echo '<p>Slots available: ' . $row['slot'] . '</p>';

                    // Link to job detail page
                    echo '<p><a href="job_detail_candidate.php?id=' . $row['id'] . '">View Details</a></p>';

                    echo '</div>';
                }

                echo '</div>';

                // Pagination
                echo "<div class='paging'>";
                echo '<p>Page: ';
                for ($i = 1; $i <= $total_pages; $i++) {
                    echo '<a href="?page=' . $i . '">' . $i . '</a>';
                }
                echo '</p>';
                echo '</div>';

            } else {
                echo '<p>No available job found!</p>';
            }
        ?>

    </main>

    <?php
        include 'includes/footer.inc';
    ?>

    <script type="text/javascript" src="js/welcome.js"></script>
</body>
</html>