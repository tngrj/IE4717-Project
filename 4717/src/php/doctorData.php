<?php
require_once 'db-connect.php';

// Check if the user is logged in as a patient
if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'patient') {

    // Initialize an array to store the results
    $doctorData = array();

    // SQL query to retrieve unique doctors with their appointment details
    $sql = "SELECT D.first_name, D.last_name, D.image, D.id as doctor_id, A.scheduled_date, A.scheduled_time
        FROM Doctor AS D
        LEFT JOIN Appointment AS A ON D.id = A.doctor_id";

    $result = $conn->query($sql);

    // Check if there are results
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $doctorName = $row['first_name'] . ' ' . $row['last_name'];
            $doctorImage = $row['image'];
            $doctorId = $row['doctor_id'];
            $scheduledDate = date("d-M-Y", strtotime($row['scheduled_date']));
            $scheduledTime = date("H:i", strtotime($row['scheduled_time']));

            // Check if the doctor already exists in the array
            if (!isset($doctorData[$doctorId])) {
                $doctorData[$doctorId] = array(
                    'doctorName' => $doctorName,
                    'doctorImage' => $doctorImage,
                    'doctorId' => $doctorId,
                    'appointments' => array()
                );
            }

            // Add the appointment data to the doctor's array
            $doctorData[$doctorId]['appointments'][] = array(
                'scheduledDate' => $scheduledDate,
                'scheduledTime' => $scheduledTime
            );
        }
    }

    $result->close();
} else {
    // Handle the case where the user is not logged in as a patient or doesn't have access
    echo "Access denied";
}
