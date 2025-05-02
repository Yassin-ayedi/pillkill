<?php
session_start();
require_once 'db.php';

$user_id = $_SESSION['user_id'] ?? null;
$appointments = [];

if ($user_id) {
    $sql = "SELECT id, doctor_name, specialty, appointment_date, appointment_time, location 
            FROM appointments 
            WHERE user_id = ?
            ORDER BY appointment_date, appointment_time";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $appointments[] = [
            'id' => (int)$row['id'],
            'doctorName' => $row['doctor_name'],
            'specialty' => $row['specialty'],
            'date' => $row['appointment_date'],
            'time' => $row['appointment_time'],
            'location' => $row['location']
        ];
    }
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link rel="stylesheet" href="service2.css?v=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
</head>
<body>
    
  <header class="header">
    <a href="home.php" class="icon-button">
      <div class="logo">
        <img src="site_icon.png" alt="Logo">
        <span class="site-name">PillKill</span>
      </div>
    </a>  
  </header>

  <main class="main">
    <h1 class="heading"><i class="fas fa-calendar-alt"></i> Doctor Appointments</h1>
    <p class="service-desc">Manage your medical appointments and rendez-vous</p>

    <div class="schedule-container">
      <div class="week-navigation">
        <button class="nav-btn" id="prevWeek"><i class="fas fa-chevron-left"></i></button>
        <div class="week-title" id="weekTitle">October 23 - 29, 2023</div>
        <button class="nav-btn" id="nextWeek"><i class="fas fa-chevron-right"></i></button>
      </div>
      
      <div class="days-header">
        <div>Sunday</div>
        <div>Monday</div>
        <div>Tuesday</div>
        <div>Wednesday</div>
        <div>Thursday</div>
        <div>Friday</div>
        <div>Saturday</div>
      </div>
      
      <div class="days-grid" id="daysGrid">
        <!-- Days js -->
      </div>
    </div>
  </main>


  <div class="modal" id="appointmentModal">
    <div class="modal-content">
      <form id="appointmentForm" action="add_appointment.php" method="POST">
        <div class="modal-header">
          <h3 class="modal-title" id="modalDayTitle">Add Doctor Appointment</h3>
          <button class="close-btn" id="closeModal">&times;</button>
        </div>
        <input type="hidden" id="selectedDate" name="date">
        <div class="form-group">
          <label for="doctorName" class="form-label">Doctor's Name</label>
          <input type="text" id="doctorName" name="doctorName" class="form-control" placeholder="Dr. Smith" required>
        </div>
        <div class="form-group">
          <label for="specialty" class="form-label">Specialty</label>
          <input type="text" id="specialty" name="specialty" class="form-control" placeholder="e.g. Cardiologist" required>
        </div>
        <div class="form-group">
          <label for="appointmentTime" class="form-label">Appointment Time</label>
          <div class="time-inputs">
            <input type="time" id="appointmentTime" name="time" class="form-control" required>
          </div>
        </div>
        <div class="form-group">
          <label for="location" class="form-label">Location</label>
          <input type="text" id="location" name="location" class="form-control" placeholder="Hospital or clinic address">
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Save Appointment</button>
        </div>
      </form>
    </div>
  </div>
  
  <script>
    let appointments  = <?php echo json_encode($appointments); ?>;
  </script>

  <script src="service2.js"></script>

</body>
</html>