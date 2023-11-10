<?php
include 'db-connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the data sent from the form
    $user_id = $_POST['user_id']; // Make sure the form includes this field
    $user_type = $_POST['user_type']; // Make sure the form includes this field

    // Update the database with the new values
    if ($user_type == 'doctor') {
        // Extract other form values
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
        $specialization = $_POST['specialization'];
        $biography = $_POST['biography'];

        $query = "UPDATE Doctor SET first_name='$first_name', last_name='$last_name', email='$email', specialization='$specialization', biography='$biography' WHERE id=$user_id";
    } else {
        // Extract other form values
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $date_of_birth = $_POST['date_of_birth'];
        $email = $_POST['email'];

        $query = "UPDATE Patient SET first_name='$first_name', last_name='$last_name', date_of_birth='$date_of_birth', email='$email' WHERE id=$user_id";
    }

    if (mysqli_query($conn, $query)) {
        // Add a success message to the array
        $messages[] = "Profile update successful!";
    } else {
        // Add an error message to the array
        $messages[] = "Profile update failed.";
    }
    // Redirect back to the profile page with the messages
    if ($user_type == 'doctor') {
        header("Location: doctorProfile.php?message=" . implode('<br>', $messages));
    } else {
        header("Location: patientProfile.php?message=" . implode('<br>', $messages));
    }
    exit();
}

$conn->close();
