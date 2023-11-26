<?php
    // Create a connection to the database
    require_once("settings.php");
    $conn = @mysqli_connect($host, $user, $pwd, $sql_db);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Search</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="styles/style.css">
</head>

<body>
    <?php
        include 'includes/header_candidate.inc';
    ?>
    
    <main>
        <h1>Search for courses</h1>

        <div>
            form here to filter
        </div>

        <?php
            $results_per_page = 12;
            if (!isset($_GET['page'])) {
                $page = 1;
            } else {
                $page = $_GET['page'];
            }
            $start_from = ($page - 1) * $results_per_page;

            // Query to get total number of rows
            $count_query = "SELECT COUNT(*) as total FROM `tblcourse`";
            $count_result = @mysqli_query($conn, $count_query);
            $count_row = mysqli_fetch_assoc($count_result);
            $total_pages = ceil($count_row['total'] / $results_per_page);

            // Query to fetch job data with LIMIT
            $query = "SELECT tblcourse.id, tblcourse.title, tblcoursespecialization.title AS specialization, tblcourse.length, tblcourse.price,
            IFNULL(AVG(tblcourseregistration.rate), 0) AS averageRate
            FROM tblcourse
            JOIN tblcoursespecialization ON tblcourse.specializationId = tblcoursespecialization.id
            LEFT JOIN tblcourseregistration ON tblcourseregistration.courseId = tblcourse.id
            GROUP BY tblcourse.title, tblcoursespecialization.title, tblcourse.length, tblcourse.price
            LIMIT $start_from, $results_per_page;";
            $result = @mysqli_query($conn, $query);

            echo '<div>';

            if ($result && mysqli_num_rows($result) > 0) {
                // Loop through the rows and display the data
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<div>';
                    echo '<h3>Course title: ' . $row['title'] . '</h3>';
                    echo '<p>Course specialization: ' . $row['specialization'] . '</p>';
                    echo '<p>Length: ' . $row['length'] . '</p>';
                    echo '<p>Price: ' . $row['price'] . '</p>';
                    echo '<p>Average rate: ' . $row['averageRate'] . '/5</p>';
                    // Link to job detail page
                    echo '<p><a href="course_info.php?id=' . $row['id'] . '">View Details</a></p>';

                    echo '</div>';
                }

                echo '</div>';
                echo '<div>';

                // Pagination
                echo '<div>';
                for ($i = 1; $i <= $total_pages; $i++) {
                    echo '<a href="?page=' . $i . '">' . $i . '</a>';
                }
                
                echo '</div>';

            } else {
                echo '<p>No available course found!</p>';
            }
        ?>
    </main>

    <?php
        include 'includes/footer.inc';
    ?>
</body>

</html