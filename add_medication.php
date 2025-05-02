<?php
session_start();
require 'db.php';

$id_user = $_SESSION['user_id']; 

if (isset($_POST['medName']) && isset($_POST['dosage']) && isset($_POST['timesPerDay'])  && isset($_POST['dose1Timing'])) {
    $medName = $_POST['medName'];
    $dosage = $_POST['dosage'];
    $timesPerDay = $_POST['timesPerDay'];
    $dose1Timing = $_POST['dose1Timing'];

    $stmt = $conn->prepare("
    INSERT INTO 
    medications (user_id, medication_name, dosage, times_per_day, when_to_take)
     VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $id_user, $medName, $dosage, $timesPerDay, $dose1Timing);

    if ($stmt->execute()) {
        // Success 
        $medication_id = $stmt->insert_id;
        $stmtDose = $conn->prepare("
        INSERT INTO 
        medication_doses (user_id, medication_id, dose_date, taken_times) 
        VALUES (?, ?, CURDATE(), 0)");
        $stmtDose->bind_param("ii", $id_user, $medication_id);
        $stmtDose->execute();
        header("Location: service1.php");
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