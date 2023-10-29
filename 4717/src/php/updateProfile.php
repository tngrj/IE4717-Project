<?php
include 'db-connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the data sent from the form
    $doctor_id = $_POST['doctor_id']; // Make sure the form includes this field

    // Extract other form values
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $specialization = $_POST['specialization'];
    $biography = $_POST['biography'];

    // Update the database with the new values
    $query = "UPDATE Doctor SET first_name=?, last_name=?, email=?, specialization=?, biography=? WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssi", $first_name, $last_name, $email, $specialization, $biography, $doctor_id);

    if ($stmt->execute()) {
        // Redirect back to the profile page after the update
        header("Location: doctorProfile.php");
        exit();
    } else {
        echo "Profile update failed.";
    }

    $stmt->close();
}

$conn->close();
