<?php
session_start();
require 'db.php';

$id_user = $_SESSION['user_id']; 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $tmpName = $_FILES['image']['tmp_name'];
    $uploadPath = 'uploads/' . uniqid() . '.jpg';
    move_uploaded_file($tmpName, $uploadPath);

    $output = shell_exec("python handwriting_ocr.py ". escapeshellarg($uploadPath) );

    // Decode the JSON result from Python
    $data = json_decode($output, true);

    if ($data && isset($data['medication_name'])) {
        echo json_encode(['success' => true] + $data);

        $medName = $data['medication_name'];
        $dosage = $data['dosage'];
        $timesPerDay = (int)$data['timesPerDay'];
        $dose1Timing = $data['when'];

        $stmt = $conn->prepare("INSERT INTO 
        medications (user_id, medication_name, dosage, times_per_day, when_to_take) 
        VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $id_user, $medName, $dosage, $timesPerDay, $dose1Timing);
    
        if ($stmt->execute()) {
            // Success 
            $medication_id = $stmt->insert_id;
            $stmtDose = $conn->prepare("INSERT INTO medication_doses (user_id, medication_id, dose_date, taken_times) VALUES (?, ?, CURDATE(), 0)");
            $stmtDose->bind_param("ii", $id_user, $medication_id);
            $stmtDose->execute();
        } else {
            // Error 
            echo "Error inserting: " . $stmt->error;
        }


    } else {
        echo json_encode(['success' => false, 'message' => 'Could not extract text']);
    }
}

?>