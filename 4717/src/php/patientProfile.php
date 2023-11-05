<?php
session_start();

include 'patientAppt.php';

// Check if the user is logged in as a patient
if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'patient') {
    $patient_id = $_SESSION['patient_id'];
    $patient_name = $_SESSION['patient_name'];
} else {
    // Handle the case where the user is not logged in as a patient
    header("Location: ../html/error.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Go Doc</title>

    <link rel="stylesheet" href="../css/style.css" />
    <link rel="stylesheet" href="../css/form.css" />
    <link rel="icon" href="../css/LogoIcon.png" />
    <script src="../js/cancelAppt.js"></script>
    <script src="../js/script.js"></script>
</head>

<body>
    <nav class="navbar">
        <a href="uMain.php" class="home-link" id="homePage"><img src="../css/logo.png" alt="Home" width="50%" /></a>
        <div class="nav-links">
            <a href="doctorList.php"><img src="../css/doctors.png" title="List of Doctors" /></a>
            <a href="patientProfile.php"><img src="../css/profile.png" title="Profile" /></a>
            <a href="#" id="logoutButton" onclick="confirmLogout();"><img src="../css/logout.png" title="Logout" width="80%" /></a>
        </div>
    </nav>
    <br />
    <form id="profile-form" method="post" action="updateProfile.php">
        <div class="profile-card">
            <div class="btn-group">
                <h1>Profile</h1>
                <button id="edit-button" type="button"><img src="../css/edit.png" class="button-image" title="Edit Profile"></button>
                <button id="confirm-button" type="submit" style="display: none;"><img src="../css/confirm.png" class="button-image" title="Confirm"></button>
            </div>
            <div class="profile-info">
                <input type="hidden" name="user_id" value="<?php echo $patient_id; ?>">
                <input type="hidden" name="user_type" value="patient">
                <?php
                include 'db-connect.php';

                $query = "SELECT * FROM Patient WHERE id = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("i", $patient_id);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    echo "<p>First Name:</p>";
                    echo "<input type='text' class='profile-section' id='first-name' name='first_name' readonly value='" . $row['first_name'] . "'>";
                    echo "<p>Last Name:</p>";
                    echo "<input type='text' class='profile-section' id='last-name' name='last_name' readonly value='" . $row['last_name'] . "'>";
                    echo "<p>Date of Birth:</p>";
                    echo "<input type='date' class='profile-section' id='dob' name='date_of_birth' readonly value='" . $row['date_of_birth'] . "'>";
                    echo "<p>Email:</p>";
                    echo "<input type='email' class='profile-section' id='email' name='email' readonly value='" . $row['email'] . "'>";
                }
                $stmt->close();
                $conn->close();
                ?>
            </div>
        </div>
    </form>

</body>

<footer>
    <p>&copy; Go Doc 2023</p>
</footer>

</html>

<script>
    function confirmLogout() {
        if (confirm('Are you sure you want to log out?')) {
            // If the user confirms, then trigger the logout process by navigating to the PHP script.
            window.location.href = 'logout.php';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const editButton = document.getElementById('edit-button');
        const confirmButton = document.getElementById('confirm-button');
        const inputFields = document.querySelectorAll('.profile-section');

        // Edit button click event
        editButton.addEventListener('click', function() {
            for (const field of inputFields) {
                field.removeAttribute('readonly');
            }
            editButton.style.display = 'none';
            confirmButton.style.display = 'block';
        });
    });
</script>