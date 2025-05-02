<?php
session_start();
$user_id = $_SESSION['user_id'];  

$servername = "localhost";
$username = "your_user";
$password = "your_pass";
$dbname = "your_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, name, dosage, times_per_day, taken_today FROM medications WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$medications = [];
while ($row = $result->fetch_assoc()) {
  $medications[] = $row;
}

$conn->close();
?>
