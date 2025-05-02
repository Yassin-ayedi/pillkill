<?php
session_start();
require 'db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'], $_POST['password'])) {

    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $pass = $_POST['password'];
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: login.html?error=invalidemail");
        exit();
    }

    $stmt = $conn->prepare("
    SELECT username, password , user_id 
    FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
    
        if (password_verify($pass, $row['password'])) {
            $_SESSION['username'] = $row['username'];
            $_SESSION['user_id'] = $row['user_id'];  
            header("Location: home.php");
            exit();
        } else {
            header("Location: login.html?error=wrong");
            exit();
        }
    } else {
        header("Location: login.html?error=notfound");
        exit();
    }
    $stmt->close();
}

$conn->close();



?>