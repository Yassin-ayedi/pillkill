document.addEventListener('DOMContentLoaded', function () {
  const mainAddBtn = document.getElementById('mainAddBtn');
  const addSection = document.getElementById('addSection');
  const closeBtn = document.getElementById('closeBtn');
  const progressTracker = document.getElementById('progressTracker');
  const carouselNav = document.getElementById('carouselNav');
  const prevBtn = document.getElementById('prevBtn');
  const nextBtn = document.getElementById('nextBtn');
  const progressCarousel = document.getElementById('progressCarousel');

  let currentSlide = 0;
  console.log(medications);



  updateProgressCircles();

  
  
  mainAddBtn.addEventListener('click', () => {
    addSection.style.display = 'flex';
  });

  closeBtn.addEventListener('click', () => {
    addSection.style.display = 'none';
  });

  function updateProgressCircles() {
    progressTracker.innerHTML = '';
    carouselNav.innerHTML = '';

    medications.forEach((med, index) => {
      const circleContainer = document.createElement('div');
      circleContainer.className = 'progress-circle-container';
      circleContainer.dataset.medId = med.id;

      circleContainer.innerHTML = `
        <div class="progress-circle">
          <svg class="progress-svg" viewBox="0 0 36 36">
            <path class="bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
            <path class="progress" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
            <text x="18" y="20.35" class="percentage">${med.takenToday}/${med.timesPerDay}</text>
          </svg>
          <p class="med-name">${med.medication_name}</p>
          <p class="progress-label">${med.dosage}mg</p>
          <div class="circle-actions">
            <button class="action-btn increment-btn" data-med-id="${med.id}">
              <i class="fas fa-plus"></i>
            </button>
            <button class="action-btn delete-btn" data-med-id="${med.id}">
              <i class="fas fa-trash"></i>
            </button>
          </div>
          <p class="last-reset">Today</p>
        </div>
      `;

      progressTracker.appendChild(circleContainer);

      setTimeout(() => {
        const progress = circleContainer.querySelector('.progress');
        const percentage = (med.takenToday / med.timesPerDay) * 100;
        progress.style.strokeDasharray = `${percentage}, 100`;
      }, 10);

      const dot = document.createElement('div');
      dot.className = 'carousel-dot';
      if (index === 0) dot.classList.add('active');
      dot.addEventListener('click', () => goToSlide(index));
      carouselNav.appendChild(dot);
    });

    progressCarousel.style.display = medications.length > 0 ? 'block' : 'none';
    updateCarouselPosition();
    addActionButtonListeners();
  }

  function addActionButtonListeners() {
    document.querySelectorAll('.increment-btn').forEach(btn => {
      btn.addEventListener('click', function () {
        const medId = parseInt(this.dataset.medId);
        incrementDose(medId);
      });
    });

    document.querySelectorAll('.delete-btn').forEach(btn => {
      btn.addEventListener('click', function () {
        const medId = parseInt(this.dataset.medId);
        deleteMedication(medId);
      });
    });
  }

  function incrementDose(medId) {
    const med = medications.find(m => m.id === medId);
    const medIndex = medications.findIndex(m => m.id === medId);
    if (!med) return;

    if (med.takenToday < med.timesPerDay) {
      fetch('increment_dose.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ medication_id: medId })
      })

      .then(() => {
        med.takenToday++;
        medications[medIndex] = med; 
        updateProgressCircles();
      })
    } else {
      alert('You have already taken all doses for today!');
    }
  }

  function deleteMedication(medId) {
    if (!confirm('Are you sure you want to delete this medication?')) return;

    fetch('delete_medication.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ medication_id: medId })
    })
    .then(() => {
      medications = medications.filter(m => m.id !== medId);
      console.log(medications);
      updateProgressCircles();

      if (currentSlide >= medications.length && medications.length > 0) {
        currentSlide = medications.length - 1;
        goToSlide(currentSlide);
      } 
      
      if (medications.length === 0) {
        currentSlide = 0;
      }
    })
    .catch(err => {alert('Failed to delete medication.')  ;console.error(err);});
  }

  function goToSlide(index) {
    currentSlide = index;
    updateCarouselPosition();
    updateActiveDot();
  }

  function updateCarouselPosition() {
    progressTracker.style.transform = `translateX(-${currentSlide * 100}%)`;
  }

  function updateActiveDot() {
    document.querySelectorAll('.carousel-dot').forEach((dot, i) => {
      dot.classList.toggle('active', i === currentSlide);
    });
  }

  prevBtn.addEventListener('click', () => {
    if (currentSlide > 0) {
      currentSlide--;
      goToSlide(currentSlide);
    }
  });

  nextBtn.addEventListener('click', () => {
    if (currentSlide < medications.length - 1) {
      currentSlide++;
      goToSlide(currentSlide);
    }
  });
});
