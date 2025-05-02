<?php
session_start();

require 'db.php';

if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
    
    $user = $_POST['username'];
    $email = $_POST['email'];
    $pass = $_POST['password'];

    $hashed_password = password_hash($pass, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $user, $email, $hashed_password);

    if ($stmt->execute()) {
        // Success 
        $_SESSION['username'] = $user;  
        $_SESSION['user_id'] = $row['user_id'];
        header("Location: home.php");
        exit();
    } else {
        // Error 
        echo "Error inserting: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
