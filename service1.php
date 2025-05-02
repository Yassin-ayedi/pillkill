<?php
session_start();
require_once 'db.php';

$user_id = $_SESSION['user_id'] ?? null;
$medications = [];

if ($user_id) {

    $sql = "SELECT 
    m.medication_id, m.medication_name, m.dosage, m.times_per_day,d.dose_date, 
                   IFNULL(d.taken_times, 0) AS taken_today
            FROM medications m
            LEFT JOIN medication_doses d 
              ON m.medication_id = d.medication_id 
              AND d.user_id = m.user_id
            WHERE m.user_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $medications[] = [
            'id' => (int)$row['medication_id'],
            'medication_name' => $row['medication_name'],
            'dosage' => $row['dosage'],
            'timesPerDay' => (int)$row['times_per_day'],
            'takenToday' => (int)$row['taken_today'],
            'date' => $row['dose_date'],
        ];
    }
}
?>




<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="service1.css?v=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>Document</title>
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
      <h1 class="heading"><i class="fas fa-pills"></i> DoseTrack</h1>
      <p class="service-desc">Track your daily medications and prescriptions.</p>

      <button class="add-btn" id="mainAddBtn"><i class="fas fa-plus"></i> Add Medication</button>


      <div class="upload-area" onclick="document.getElementById('fileInput').click();">
        <i class="fas fa-cloud-upload-alt"></i>
        <p>Drag or click to upload your prescription</p>
        <input type="file" id="fileInput" accept="image/*" hidden />
      </div>

 
      <div class="progress-carousel" id="progressCarousel">
        <button class="carousel-arrow left" id="prevBtn"><i class="fas fa-chevron-left"></i></button>
        <button class="carousel-arrow right" id="nextBtn"><i class="fas fa-chevron-right"></i></button>
        
        <div class="progress-tracker" id="progressTracker">
          <!-- Progress circles js -->
        </div>
        
        <div class="carousel-nav" id="carouselNav">
          <!-- Navigation dots js -->
        </div>
        
        <p class="swipe-instruction">Swipe left or right to view all medications</p>
      </div>


      <div class="add-section" id="addSection">
        <div class="add-section-box">
          <div class="add-section-header">
            <h3>Add Medicament</h3>
            <button class="close-btn" id="closeBtn"><i class="fas fa-times"></i></button>
          </div> 
          <div class="separator"></div>

          <div class="form-group" id="doseFields">

            <form action="add_medication.php" method="POST">

              <label for="medName" class="form-label">Medication Name</label>
              <input type="text" id="medName" name="medName" class="form-control" placeholder="e.g. doliprane" required>
              

              <label for="dosage" class="form-label">Dosage (mg or ml)</label>
              <input type="number" id="dosage" name="dosage" class="form-control" placeholder="e.g. 500" required>
              

              <label for="timesPerDay" class="form-label">Times per Day</label>
              <input type="number" id="timesPerDay" name="timesPerDay" class="form-control" placeholder="e.g. 2" required>
              

              <div class="dose-field">
                <label for="dose1Timing" class="form-label">When to take?</label>
                <select id="dose1Timing" name="dose1Timing" class="form-control" required>
                  <option value="">Select timing</option>
                  <option value="before">Before Meal</option>
                  <option value="after">After Meal</option>
                  <option value="other">Other</option>
                </select>
              </div>
            

              <button type="submit" id="submitMedBtn" class="add-btn">Save Medication</button>
            </form>
          </div>
        </div>
      </div>
    </main>
  </body>


  <script>
    document.getElementById('fileInput').addEventListener('change', function () {
      const file = this.files[0];
      if (!file) {return;console.error('No file selected');}
      const formData = new FormData();
      formData.append('image', file);

      fetch('ocr.php', {
      method: 'POST',
      body: formData
    })
    .then(res => res.text()) 
    .then(text => {
      console.log('Raw response:', text);

        const jsonMatch = text.match(/{.*}/s);

        const data = JSON.parse(jsonMatch[0]);

        if (data.success) {
          console.log('Parsed data:', data);

        } else {
          alert('Text recognition failed: ' + data.message);
        }

    })

    .catch(err => {
      console.error('Fetch error:', err);
      alert('OCR error occurred');
    });

    });
  </script>

  <script>let medications = <?php echo json_encode($medications); ?>;</script>

  <script src="service1.js"></script>
</html>