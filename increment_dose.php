
<?php
session_start();
header('Content-Type: application/json');
require 'db.php';

$user_id = $_SESSION['user_id'] ?? 1;
$data = json_decode(file_get_contents('php://input'), true);
$med_id = $data['medication_id'];
$today = date('Y-m-d');

$sql = "UPDATE medication_doses
        SET taken_times = taken_times + 1
        WHERE user_id = ? AND medication_id = ? AND dose_date = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('iis', $user_id, $med_id, $today);
$stmt->execute();
$stmt->close();

?>
