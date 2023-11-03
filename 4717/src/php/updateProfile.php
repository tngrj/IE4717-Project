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

        $query = "UPDATE Doctor SET first_name=?, last_name=?, email=?, specialization=?, biography=? WHERE id=?";
    } else {

        // Extract other form values
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $date_of_birth = $_POST['date_of_birth'];
        $email = $_POST['email'];

        $query = "UPDATE Patient SET first_name=?, last_name=? , date_of_birth=? , email=? WHERE id=?";
    }
    $stmt = $conn->prepare($query);
    if ($user_type == 'doctor') {
        $stmt->bind_param("sssssi", $first_name, $last_name, $email, $specialization, $biography, $user_id);
    } else {
        $stmt->bind_param("ssssi", $first_name, $last_name, $date_of_birth, $email, $user_id);
    }

    if ($stmt->execute()) {
        // Redirect back to the profile page after the update
        if ($user_type == 'doctor') {
            header("Location: doctorProfile.php");
        } else {
            header("Location: patientProfile.php");
        }
        exit();
    } else {
        echo "Profile update failed.";
    }

    $stmt->close();
}

$conn->close();
