<?php
session_start();

// Check if the user is logged in as a doctor
if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'doctor') {
    $doctor_id = $_SESSION['doctor_id'];
} else {
    // Handle the case where the user is not logged in as a doctor
    header("Location: ../html/error.html");
    exit();
}
?>

<?php
if (isset($_GET['message'])) {
    $messages = explode('||', $_GET['message']);
    foreach ($messages as $msg) {
        echo '<div id="popup" class="message"><span>' . $msg  . '</span><button class="button-image"><img src="../css/cancel.png" ></button> </div>';
    }
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
    <link rel="icon" href="../css/logo.png" />
    <script src="../js/script.js"></script>
</head>

<body>
    <nav class="navbar">
        <a href="aMain.php" class="home-link" id="homePage"><img src="../css/logo.png" alt="Home" width="50%" /></a>
        <div class="nav-links">
            <a href="aMain.php"><img src="../css/calendar.png" title="Calendar" /></a>
            <a href="adminProfile.php"><img src="../css/profile.png" title="Profile" /></a>
            <a href="#" id="logoutButton" onclick="confirmLogout();"><img src="../css/logout.png" title="Logout" width="80%" /></a>
        </div>
    </nav>
    <br />
    <form id="profile-form" method="post" action="updateProfile.php">
        <div class="profile-card">
            <div class="btn-group">
                <h1>Profile</h1>
                <button id="edit-button" class="profile-btn" type="button"><img src="../css/edit.png" title="Edit Profile"></button>
                <button id="confirm-button" class="profile-btn" type="submit" style="display: none;"><img src="../css/confirm.png" title="Confirm"></button>
            </div>
            <div class="profile-info">
                <input type="hidden" name="user_id" value="<?php echo $doctor_id; ?>">
                <input type="hidden" name="user_type" value="doctor">
                <?php
                include 'db-connect.php';

                $doctor_id = $_SESSION['doctor_id'];

                $query = "SELECT * FROM Doctor WHERE id = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("i", $doctor_id);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    echo "<p>First Name:</p>";
                    echo "<input type='text' class='profile-section' id='first-name' name='first_name' readonly value='" . $row['first_name'] . "'>";
                    echo "<p>Last Name:</p>";
                    echo "<input type='text' class='profile-section' id='last-name' name='last_name' readonly value='" . $row['last_name'] . "'>";
                    echo "<p>Email:</p>";
                    echo "<input type='email' class='profile-section' id='email' name='email' readonly value='" . $row['email'] . "'>";
                    echo "<p>Specialization:</p>";
                    echo "<input type='text' class='profile-section' id='specialization' name='specialization' readonly value='" . $row['specialization'] . "'>";
                    echo "<p>Biography:</p>";
                    echo "<textarea class='profile-section' id='biography' name='biography' readonly>" . $row['biography'] . "</textarea>";
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