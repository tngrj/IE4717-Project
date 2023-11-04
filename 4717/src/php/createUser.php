<?php
require_once 'db-connect.php';

// Get form data
$firstname = $_POST['addfirstname'];
$lastname = $_POST['addlastname'];
$dob = $_POST['adddob'];
$email = $_POST['addemail'];
$password = $_POST['addpassword'];
$accesscode = $_POST['accesscode'];

// Initialize the SQL variable
$sql = '';

// Check if the provided access code matches the doctor code
if ($accesscode === '5564') {
    // Insert data into the Doctor table if the access code matches
    $specialization = $_POST['addspecialization'];
    $biography = $_POST['addbiography'];
    $image = $_FILES['addimage']['name'];
    $image_tmp_name = $_FILES['addimage']['tmp_name'];
    $image_folder = '../assets/' . $image;

    // Check if the file is a valid upload
    if (is_uploaded_file($image_tmp_name)) {
        if (move_uploaded_file($image_tmp_name, $image_folder)) {
            // Image uploaded successfully
            $sql = "INSERT INTO Doctor (first_name, last_name, date_of_birth, email, password, specialization, biography, image)
                VALUES ('$firstname', '$lastname', '$dob', '$email', '$password', '$specialization', '$biography', '$image')";
        } else {
            // Image upload failed
            echo "Error uploading image";
        }
    } else {
        echo "Invalid file upload";
    }
} else {
    // Insert data into the Patient table if the access code doesn't match
    $sql = "INSERT INTO Patient (first_name, last_name, date_of_birth, email, password)
    VALUES ('$firstname', '$lastname', '$dob', '$email', '$password')";
}

if (!empty($sql)) {
    if ($conn->query($sql) === TRUE) {
        header("Location: ../html/LandingPage.html");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
