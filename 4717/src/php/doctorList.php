<?php
session_start();

include 'patientAppt.php';
include 'db-connect.php';

// Check if the user is logged in as a patient
if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'patient') {
    $patient_id = $_SESSION['patient_id'];
    $patient_name = $_SESSION['patient_name'];
} else {
    // Handle the case where the user is not logged in as a patient
    header("Location: ../html/error.html");
    exit();
}

// SQL query to retrieve all doctors
$sql = "SELECT * FROM Doctor";
$result = $conn->query($sql);

// Close the database connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<!----------- HEAD --------------------------------------->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Go Doc</title>

    <link rel="stylesheet" href="../css/style.css" />
    <link rel="icon" href="../css/logo.png" />
    <script src="../js/script.js"></script>
</head>
<!----------- HEAD --------------------------------------->

<!----------- BODY --------------------------------------->

<body>
    <!-----NAVBAR--------------------------------------------------------------->
    <nav class="navbar">
        <a href="uMain.php" class="home-link" id="homePage"><img src="../css/logo.png" alt="Home" width="50%" /></a>
        <div class="nav-links">
            <a href="doctorList.php"><img src="../css/doctors.png" title="List of Doctors" /></a>
            <a href="userProfile.php"><img src="../css/profile.png" title="Profile" /></a>
            <a href="#" id="logoutButton" onclick="confirmLogout();"><img src="../css/logout.png" title="Logout" width="80%" /></a>
        </div>
    </nav>
    <br />
    <!-----END: NAVBAR---------------------------------------------------------->
    <div class="">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $doctorName = $row['first_name'] . ' ' . $row['last_name'];
                $doctorEmail = $row['email'];
                $specialization = $row['specialization'];
                $biography = $row['biography'];

                echo '<div class="doctor-card">';
                echo '<h3>' . $doctorName . '</h3>';
                echo '<p>Email: ' . $doctorEmail . '</p>';
                echo '<p>Specialization: ' . $specialization . '</p>';
                echo '<p>Biography: ' . $biography . '</p>';
                echo '</div>';
            }
        } else {
            echo '<p>No doctors found.</p>';
        }
        ?>
    </div>


</body>
<!----------- HEAD --------------------------------------->

<!----------- FOOTER ------------------------------------->
<br /><br /><br /><br /><br />
<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; Go Doc 2023</p>
    </div>
</footer>
<!----------- FOOTER ------------------------------------->

</html>


<script>
    function confirmLogout() {
        if (confirm('Are you sure you want to log out?')) {
            // If the user confirms, then trigger the logout process by navigating to the PHP script.
            window.location.href = 'logout.php';
        }
    }
</script>