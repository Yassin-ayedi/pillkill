
<?php
session_start();
header('Content-Type: application/json');
require 'db.php';

$user_id = $_SESSION['user_id'] ?? 1;
$data = json_decode(file_get_contents('php://input'), true);
$med_id = $data['medication_id'];

$sql = "DELETE FROM medications WHERE medication_id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $med_id, $user_id);
$stmt->execute();

$stmt->close();
$conn->close();

?>
