<?php
session_start();

require_once("settings.php");
$conn = @mysqli_connect($host, $user, $pwd, $sql_db) or die ("Unable to connect");

if(!isset($_SESSION['loggedIn'])){
    header('location:index.php');
}

$canId = $_SESSION['candidateId'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My online CV</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <?php
        include 'includes/header_candidate.inc';
    ?>
    <main>

    <h1>My online CV</h1>

    <div>
        <h2>My experiences</h2>
        <?php
            // Fetch candidate's previous experiences
            $fetchExperiencesQuery = "SELECT id, dateStart, dateEnd, organization, role FROM tblcandidateexperience WHERE candidateId = $canId";
            $fetchExperiencesResult = mysqli_query($conn, $fetchExperiencesQuery);

            // Check if there are any experiences
            if (mysqli_num_rows($fetchExperiencesResult) > 0) {
                echo "<table border='1'>
                        <tr>
                            <th>Date Start</th>
                            <th>Date End</th>
                            <th>Organization</th>
                            <th>Role</th>
                            <th>Action</th>
                        </tr>";

                // Output data of each row
                while ($row = mysqli_fetch_assoc($fetchExperiencesResult)) {
                    echo "<tr>
                            <td>" . $row['dateStart'] . "</td>
                            <td>" . $row['dateEnd'] . "</td>
                            <td>" . $row['organization'] . "</td>
                            <td>" . $row['role'] . "</td>
                            <td>
                                <a href='edit_experience.php?id=" . $row['id'] . "'>Edit</a>
                                <a href='delete_experience.php?id=" . $row['id'] . "'>Delete</a>
                            </td>
                        </tr>";
                }

                echo "</table>";
            } else {
                echo "No previous experiences found.";
            }
        ?>
    </div>
    
    <div>
        <h2>Add New Experience</h2>
        <form method="post" action="add_experience.php">
            <div>
                <label for="dateStart">Date Start:</label>
                <input type="date" name="dateStart" required>
            </div>
            <div>
                <label for="dateEnd">Date End:</label>
                <input type="date" name="dateEnd" required>
            </div>
            <div>
                <label for="role">Role:</label>
                <input type="text" name="role" required>
            </div>
            <div>
                <label for="organization">Organization:</label>
                <input type="text" name="organization" required>
            </div>
            <div>
                <button type="submit">Add Experience</button>
            </div>
        </form>
    </div>

    </main>
    <?php
        include 'includes/footer.inc';
    ?>
</body>
</html>