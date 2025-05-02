<?php
session_start();
require 'db.php';


$user_id = $_SESSION['user_id']; 



if (isset($_POST['date']) && isset($_POST['doctorName']) && isset($_POST['specialty'])  && isset($_POST['time']) && isset($_POST['location'])) {
    
    $doctorName = $_POST['doctorName'];
    $specialty = $_POST['specialty'];
    $time = $_POST['time'];
    $date = $_POST['date'];
    $location = $_POST['location'];

    $sql = "INSERT INTO appointments (user_id, doctor_name, specialty, appointment_date, appointment_time, location)
    VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssss", $user_id, $doctorName, $specialty, $date, $time, $location);

    if ($stmt->execute()) { 
        header("Location: service2.php");
        exit();
    } else {
        // Error 
        echo "Error inserting: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Required fields are missing.";
}


?>