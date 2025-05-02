<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="home.css?=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>
<body>
    <header class="header">
        <div class="logo">
        <img src="site_icon.png" alt="Logo">
        <span class="site-name">PillKill</span>
        </div>

        <div class="header-right">
            <div class="user-box">Welcome, <?php echo htmlspecialchars($username); ?></div>
            <div class="info" id="logout"><a href="logout.php" style="text-decoration: none; color: white;">Logout</a></div>
        </div>


        
    </header>

    <main>
        <div class="page-info">
            <h2>Never Miss a Dose Again</h2>
            <p>PillKill helps you manage your medications, appointments, and health goals <br>with personalized reminders and tracking.</p>
        </div>
        
        <div class="services">
            <a href="service1.php" class="service-button">
                <div class="service-icon">
                    <i class="fas fa-pills"></i>
                </div>
                <span class="service-title">DoseTrack</span>
                <span class="service-desc">Never forget a dose again.
                    Set schedules and get reminders for your medications.</span>
            </a>
            <a href="service2.php" class="service-button">
                <div class="service-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <span class="service-title">Health Scheduler</span>
                <span class="service-desc">Keep track of upcoming appointments.
                    Add, view, and organize your medical schedule.</span>
            </a>
            <a href="service3.php" class="service-button">
                <div class="service-icon">
                    <i class="fa-solid fa-phone"></i>   
                </div>
                <span class="service-title">Health Emergency</span>
                <span class="service-desc">Instantly call your doctor or emergency services.
                    Quick access when you need it most.</span>
            </a>
            <!-- <a href="service4.html" class="service-button">
                <div class="service-icon">
                    <i class="fa-solid fa-map"></i>
                </div>
                <span class="service-title">MediFinder</span>
                <span class="service-desc">Search for your medication and find the closest pharmacy.
                    Simple and fast.</span>
            </a> -->
        </div>
    </main>
    

</body>
</html>