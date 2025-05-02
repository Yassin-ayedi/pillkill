<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Emergency Medical Services | PillKill</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="service3.css?=1.0" />
</head>
<body>

  <!-- Header -->
  <header class="header">
    <a href="home.php" class="icon-button">
      <div class="logo">
        <img src="site_icon.png" alt="Logo">
        <span class="site-name">PillKill</span>
      </div>
    </a>
  </header>

  <!-- Emergency Medical Services -->
  <div class="emergency-actions">
    <div class="emergency-btn primary" data-number="190">
      <div class="emergency-icon"><i class="fas fa-ambulance"></i></div>
      <h3>Ambulance</h3>
    </div>

    <div class="emergency-btn" data-number="190">
      <div class="emergency-icon"><i class="fas fa-user-md"></i></div>
      <h3>SAMU</h3>
    </div>

    <div class="emergency-btn" data-number="hospital">
      <div class="emergency-icon"><i class="fas fa-hospital"></i></div>
      <h3>Nearest Hospital</h3>
    </div>

    <div class="emergency-btn" data-number="pharmacy">
      <div class="emergency-icon"><i class="fas fa-pills"></i></div>
      <h3>Pharmacy</h3>
    </div>
  </div>

  <script>
    document.querySelectorAll('.emergency-btn').forEach(btn => {
      btn.addEventListener('click', function () {
        const service = this.querySelector('h3').textContent;
        alert(`Calling ${service}...`);
      });
    });
  </script>
</body>
</html>
