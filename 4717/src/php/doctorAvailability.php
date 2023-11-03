<?php
require_once 'db-connect.php';

if (isset($_POST['selectedDate']) && isset($_POST['doctorid'])) {
    $selectedDate = $_POST['selectedDate'];
    $doctorId = $_POST['doctorid'];

    $sql = "SELECT scheduled_time 
            FROM appointment 
            WHERE status = 'Confirmed'
            AND doctor_id = $doctorId
            AND scheduled_date = '$selectedDate'";

    $availableTimeSlots = [];

    if ($result = $conn->query($sql)) {
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $availableTimeSlots[] = $row['scheduled_time'];
            }
            $result->free();
        } 
    } 

    $_SESSION['availableTimeSlots'] = $availableTimeSlots;
   // $jsonResponse = json_encode($availableTimeSlots);
   // echo $jsonResponse;

    $conn->close();
}
?>