document.addEventListener('DOMContentLoaded', function () {

  const daysGrid = document.getElementById('daysGrid');
  const weekTitle = document.getElementById('weekTitle');
  const prevWeekBtn = document.getElementById('prevWeek');
  const nextWeekBtn = document.getElementById('nextWeek');
  const appointmentModal = document.getElementById('appointmentModal');
  const closeModalBtn = document.getElementById('closeModal');
  const appointmentForm = document.getElementById('appointmentForm');
  const modalDayTitle = document.getElementById('modalDayTitle');
  const selectedDateInput = document.getElementById('selectedDate');

  let currentWeekStart = getStartOfWeek(new Date());

  const groupedAppointments = {};
  appointments.forEach(appt => {
    const date = appt.date;
    if (!groupedAppointments[date]) {
      groupedAppointments[date] = [];
    }
    groupedAppointments[date].push(appt);
  });

  appointments = groupedAppointments;

  renderWeek();

  prevWeekBtn.addEventListener('click', () => {
    currentWeekStart.setDate(currentWeekStart.getDate() - 7);
    renderWeek();
  });

  nextWeekBtn.addEventListener('click', () => {
    currentWeekStart.setDate(currentWeekStart.getDate() + 7);
    renderWeek();
  });

  closeModalBtn.addEventListener('click', closeModal);

  function renderWeek() {
    daysGrid.innerHTML = '';

    const weekEnd = new Date(currentWeekStart);
    weekEnd.setDate(weekEnd.getDate() + 6);
    weekTitle.textContent = formatWeekTitle(currentWeekStart, weekEnd);

    const today = new Date();
    const todayString = formatDate(today);

    for (let i = 0; i < 7; i++) {
      const day = new Date(currentWeekStart);
      day.setDate(day.getDate() + i);

      const dayString = formatDate(day);
      const dayNumber = day.getDate();

      const dayCell = document.createElement('div');
      dayCell.className = 'day-cell';
      if (dayString === todayString) {
        dayCell.classList.add('today');
      }

      dayCell.innerHTML = `
        <div class="day-number">${dayNumber}</div>
        <div class="appointment-items" id="appointments-${dayString}"></div>
        <button class="add-appointment-btn" data-date="${dayString}">
          <i class="fas fa-plus"></i>
        </button>
      `;

      daysGrid.appendChild(dayCell);
      renderDayAppointments(dayString);
    }

    document.querySelectorAll('.add-appointment-btn').forEach(btn => {
      btn.addEventListener('click', function () {
        const date = this.dataset.date;
        openAddAppointmentModal(date);
      });
    });
  }

  function renderDayAppointments(dateString) {
    const container = document.getElementById(`appointments-${dateString}`);
    if (!container) return;

    container.innerHTML = '';
    const dayAppointments = appointments[dateString] || [];

    dayAppointments.forEach(appt => {
      const appointmentItem = document.createElement('div');
      appointmentItem.className = 'appointment-item';
      appointmentItem.innerHTML = `
        <div class="appointment-time">${formatTime(appt.time)}</div>
        <div class="appointment-doctor">${appt.doctorName}</div>
        <div class="appointment-specialty">${appt.specialty}</div>
        <button class="delete-appointment-btn" data-id="${appt.id}" data-date="${dateString}">
          <i class="fas fa-trash"></i>
        </button>
      `;
      container.appendChild(appointmentItem);
    });

    // ✅ Attach listeners ONLY to buttons inside this day's container
    container.querySelectorAll('.delete-appointment-btn').forEach(btn => {
      btn.addEventListener('click', function () {
        const id = this.dataset.id;
        const date = this.dataset.date;
        deleteAppointment(id, date);
      });
    });
  }

  function deleteAppointment(id, date) {
    if (!confirm("Are you sure you want to delete this appointment?")) return;

    fetch('delete_appointment.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `id=${encodeURIComponent(id)}`
    })
    .then(response => response.text())
    .then(result => {
      // Remove from local object
      appointments[date] = appointments[date].filter(appt => appt.id != id);
      renderDayAppointments(date);
    })
    .catch(err => {
      console.error('Error deleting appointment:', err);
    });
  }

  function openAddAppointmentModal(dateString) {
    const date = new Date(dateString);
    modalDayTitle.textContent = `Add Appointment for ${date.toLocaleDateString('en-US', { weekday: 'long', month: 'long', day: 'numeric' })}`;
    selectedDateInput.value = dateString;
    appointmentModal.style.display = 'flex';
  }

  function closeModal() {
    appointmentModal.style.display = 'none';
  }

  function saveAppointment() {
    const date = selectedDateInput.value;
    appointments[date].sort((a, b) => a.time.localeCompare(b.time));
    renderDayAppointments(date);
    closeModal();
    appointmentForm.reset();
  }

  function getStartOfWeek(date) {
    const day = date.getDay();
    const diff = date.getDate() - day + (day === 0 ? -6 : 1);
    return new Date(date.setDate(diff));
  }

  function formatWeekTitle(start, end) {
    const options = { month: 'long', day: 'numeric' };
    return `${start.toLocaleDateString('en-US', options)} - ${end.toLocaleDateString('en-US', options)}, ${end.getFullYear()}`;
  }

  function formatDate(date) {
    return date.toISOString().split('T')[0];
  }

  function formatTime(timeString) {
    const [hours, minutes] = timeString.split(':');
    const hour = parseInt(hours);
    const ampm = hour >= 12 ? 'PM' : 'AM';
    const displayHour = hour % 12 || 12;
    return `${displayHour}:${minutes} ${ampm}`;
  }

});
