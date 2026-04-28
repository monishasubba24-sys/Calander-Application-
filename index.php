<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Calendar App</title>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
<!-- Bootstrap Icons for moon/sun -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
<!-- FullCalendar CSS -->
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.19/index.global.min.js'></script>



<style>
  /* Body and layout */
  body {
    background: #f8f9fa;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    transition: background-color 0.4s ease, color 0.4s ease;
  }
  body.dark-mode {
    background: #121212;
    color: #ddd;
  }

  /* Navbar */
  .navbar {
    transition: background-color 0.4s ease;
    background-color: #f8f9fa;
  }
  .navbar.dark-mode {
    background-color: #1f1f1f !important;
  }
  .navbar-brand {
    font-weight: 700;
    font-size: 1.5rem;
  }

  .navbar .ms-auto {
    display: flex;
    align-items: center;
    gap: 1rem;
  }

  /* Dark mode toggle */
  .form-check.form-switch {
    user-select: none;
    cursor: pointer;
  }
  .form-check.form-switch input[type="checkbox"] {
    cursor: pointer;
  }
  .form-check.form-switch label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
  }
  .form-check.form-switch label i {
    font-size: 1.2rem;
  }

  /* Calendar container */
  .calendar-container {
    background: white;
    padding: 1.5rem;
    margin-top: 1.5rem;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgb(0 0 0 / 0.1);
    flex-grow: 1;
    transition: background-color 0.4s ease, color 0.4s ease;
  }
  body.dark-mode .calendar-container {
    background: #222;
    color: #eee;
    box-shadow: 0 4px 20px rgba(255 255 255 / 0.1);
  }

  /* FullCalendar weekday headers */
  .fc-theme-standard .fc-col-header-cell-cushion {
    color: black !important;
    text-decoration: none !important;
    font-weight: 600;
    user-select: none;
    cursor: default;
    transition: color 0.3s ease;
  }
  body.dark-mode .fc-theme-standard .fc-col-header-cell-cushion {
    color: white !important;
  }

  /* FullCalendar day numbers */
  .fc-daygrid-day-number {
    color: black !important;
    text-decoration: none !important;
    font-weight: 500;
    user-select: none;
    cursor: default;
    transition: color 0.3s ease;
  }
  body.dark-mode .fc-daygrid-day-number {
    color: white !important;
  }

  /* Prevent calendar links from underline */
  .fc-event, .fc-daygrid-day-top a {
    text-decoration: none !important;
    color: inherit !important;
  }

  /* Hover effect for events */
  .fc-event:hover {
    filter: brightness(90%);
    cursor: pointer;
  }

  /* Highlight today */
  .fc-daygrid-day.fc-day-today {
    background-color: #e8f0fe;
    border-radius: 8px;
  }
  body.dark-mode .fc-daygrid-day.fc-day-today {
    background-color: #333844;
  }

  /* Modal styling */
  .modal-body label {
    font-weight: 600;
  }
  .modal-body input[type="text"],
  .modal-body input[type="password"],
  .modal-body input[type="datetime-local"],
  .modal-body input[type="color"] {
    border-radius: 6px;
  }
  body.dark-mode .modal-content {
    background-color: #2c2c2c;
    color: #eee;
  }
  body.dark-mode .modal-content input,
  body.dark-mode .modal-content select {
    background-color: #3a3a3a;
    color: #eee;
    border-color: #555;
  }
  body.dark-mode .modal-content input::placeholder {
    color: #bbb;
  }

  /* Footer */
  footer {
    background-color: #f8f9fa;
    color: #333;
    text-align: center;
    padding: 1.75rem;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    transition: background-color 0.4s ease, color 0.4s ease;
  }
  body.dark-mode footer {
    background-color: #121212;
    color: #888;
  }
  footer p {
    margin: 0.25rem 0;
  }
  .copyright {
    font-size: 1.125rem;
    font-weight: 600;
  }
  .credit {
    font-size: 0.875rem;
    color: #666;
  }
  .heart {
    color: #e25555;
    display: inline-block;
    animation: pulse 2s infinite ease-in-out;
  }
  @keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
  }

  /* Button spacing */
  .modal-footer button {
    min-width: 90px;
  }

  /* Dark mode buttons */
  body.dark-mode .btn-outline-light {
    color: #ddd;
    border-color: #ddd;
  }
  body.dark-mode .btn-outline-light:hover,
  body.dark-mode .btn-outline-light:focus {
    background-color: #444;
    color: #fff;
  }
  body.dark-mode .btn-light {
    background-color: #444;
    color: #eee;
  }
  body.dark-mode .btn-light:hover,
  body.dark-mode .btn-light:focus {
    background-color: #555;
  }
  body.dark-mode .btn-success {
    background-color: #28a745;
    border-color: #28a745;
  }
  body.dark-mode .btn-success:hover,
  body.dark-mode .btn-success:focus {
    background-color: #218838;
    border-color: #1e7e34;
  }

  /* Focus outlines for accessibility */
  button:focus, input:focus, select:focus, .form-check-input:focus {
    outline: 3px solid #0d6efd;
    outline-offset: 2px;
  }

  /* Responsive adjustments */
  @media (max-width: 576px) {
    .navbar .ms-auto {
      flex-direction: column;
      gap: 0.5rem;
      align-items: flex-start;
    }
    .calendar-container {
      padding: 1rem;
      margin-top: 1rem;
    }
    .modal-dialog {
      max-width: 90vw;
    }
  }

  /* Show/hide password toggle */
  .password-toggle {
    position: relative;
  }
  .password-toggle-btn {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    border: none;
    background: transparent;
    cursor: pointer;
    color: #6c757d;
    font-size: 1.1rem;
  }
  .password-toggle-btn:focus {
    outline: none;
    color: #0d6efd;
  }

</style>

</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark" id="mainNavbar" role="navigation" aria-label="Main navigation">
  <div class="container">
    <a class="navbar-brand" href="#">Calendar App</a>
    <div class="ms-auto d-flex align-items-center gap-3">

      <!-- Dark mode toggle -->
      <div class="form-check form-switch text-light d-flex align-items-center" role="switch" aria-checked="false" tabindex="0" id="darkModeToggleWrapper">
        <input class="form-check-input" type="checkbox" id="darkModeToggle" aria-label="Toggle dark mode" />
        <label class="form-check-label mb-0 ms-2" for="darkModeToggle">
          <i class="bi bi-moon-fill" id="darkModeIcon" aria-hidden="true"></i>
        </label>
      </div>

      <?php if (isset($_SESSION['user'])): ?>
        <div class="dropdown ms-3">
          <button
            class="btn btn-outline-light btn-sm dropdown-toggle"
            type="button"
            id="userDropdown"
            data-bs-toggle="dropdown"
            aria-expanded="false"
            aria-haspopup="true"
            aria-label="User menu for <?= htmlspecialchars($_SESSION['user']['username']) ?>"
          >
            <?= htmlspecialchars($_SESSION['user']['username']) ?>
          </button>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
            <li>
              <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                Edit Profile
              </a>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
          </ul>
        </div>
      <?php else: ?>
        <a href="login.php" class="btn btn-outline-light btn-sm me-2">Login</a>
        <a href="register.php" class="btn btn-light btn-sm">Register</a>
      <?php endif; ?>

    </div>
  </div>
</nav>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="editProfileForm" method="post" action="update_profile.php" class="modal-content" novalidate>
      <div class="modal-header">
        <h5 class="modal-title" id="editProfileLabel">Edit Profile</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

        <div class="mb-3">
          <label for="usernameInput" class="form-label">Username</label>
          <input
            type="text"
            name="username"
            id="usernameInput"
            class="form-control"
            value="<?= htmlspecialchars($_SESSION['user']['username']) ?>"
            required
            aria-describedby="usernameHelp"
          />
          <div id="usernameHelp" class="form-text">Your public username.</div>
        </div>

        <div class="mb-3 password-toggle">
          <label for="passwordInput" class="form-label">New Password</label>
          <input
            type="password"
            name="password"
            id="passwordInput"
            class="form-control"
            placeholder="Leave blank to keep current password"
            aria-describedby="passwordHelp"
          />
          <button type="button" class="password-toggle-btn" aria-label="Toggle password visibility" tabindex="0" id="togglePasswordBtn"><i class="bi bi-eye"></i></button>
          <div id="passwordHelp" class="form-text">Enter a new password if you want to change it.</div>
        </div>

        <div class="mb-3 password-toggle">
          <label for="confirmPasswordInput" class="form-label">Confirm New Password</label>
          <input
            type="password"
            name="confirm_password"
            id="confirmPasswordInput"
            class="form-control"
            placeholder="Repeat new password"
            aria-describedby="confirmPasswordHelp"
          />
          <button type="button" class="password-toggle-btn" aria-label="Toggle confirm password visibility" tabindex="0" id="toggleConfirmPasswordBtn"><i class="bi bi-eye"></i></button>
          <div id="confirmPasswordHelp" class="form-text text-danger" style="display:none;">Passwords do not match.</div>
        </div>

      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success" disabled id="saveProfileBtn">Save Changes</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
    </form>
  </div>
</div>

<!-- Calendar container -->
<div class="container calendar-container" id="calendarContainer" role="main" aria-label="Calendar">
  <div id="calendar"></div>
</div>

<!-- Event Modal -->
<div class="modal fade" id="eventModal" tabindex="-1" aria-hidden="true" aria-labelledby="eventModalLabel">
  <div class="modal-dialog">
    <form id="eventForm" class="modal-content" novalidate>
      <div class="modal-header">
        <h5 class="modal-title" id="eventModalLabel">Event</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="eventId" name="id" />
        
        <div class="mb-3">
          <label for="eventTitle" class="form-label">Title</label>
          <input
            type="text"
            class="form-control"
            name="title"
            id="eventTitle"
            placeholder="Event title"
            required
            aria-describedby="titleHelp"
          />
          <div id="titleHelp" class="form-text text-danger">Give your event a clear title.</div>
        </div>

        <div class="mb-3">
          <label for="eventStart" class="form-label">Start</label>
          <input
            type="datetime-local"
            class="form-control"
            name="start"
            id="eventStart"
            required
            aria-describedby="startHelp"
          />
          <div id="startHelp" class="form-text text-danger">Select start date and time.</div>
        </div>

        <div class="mb-3">
          <label for="eventEnd" class="form-label">End</label>
          <input
            type="datetime-local"
            class="form-control"
            name="end"
            id="eventEnd"
            required
            aria-describedby="endHelp"
          />
          <div id="endHelp" class="form-text text-danger">Select end date and time (must be after start).</div>
        </div>

        <div class="mb-3">
          <label for="eventColor" class="form-label">Color</label>
          <input
            type="color"
            class="form-control form-control-color"
            name="color"
            id="eventColor"
            title="Choose event color"
            aria-label="Choose event color"
            value="#3788d8"
          />
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" id="deleteEventBtn" class="btn btn-danger me-auto" style="display:none;">Delete</button>
        <button type="submit" class="btn btn-success" id="saveEventBtn">Save</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
    </form>
  </div>
</div>

<footer>
  <p class="copyright">&copy; <?= date('Y') ?> Calendar App</p>
  <p class="credit">Made with <span class="heart" aria-label="love">♥</span> by <a href="mailto:contact@calendarapp.dev" style="color: inherit; text-decoration: underline;">Calendar App Team</a></p>
</footer>

<!-- Bootstrap Bundle JS (includes Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- FullCalendar JS -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
  // Elements
  const body = document.body;
  const darkModeToggle = document.getElementById('darkModeToggle');
  const darkModeIcon = document.getElementById('darkModeIcon');
  const darkModeToggleWrapper = document.getElementById('darkModeToggleWrapper');

  // Load dark mode preference from localStorage
  if (localStorage.getItem('darkMode') === 'true') {
    darkModeToggle.checked = true;
    body.classList.add('dark-mode');
    document.getElementById('mainNavbar').classList.add('dark-mode');
  }
  updateDarkModeIcon();

  // Dark mode toggle handler
  darkModeToggle.addEventListener('change', () => {
    if (darkModeToggle.checked) {
      body.classList.add('dark-mode');
      document.getElementById('mainNavbar').classList.add('dark-mode');
      localStorage.setItem('darkMode', 'true');
    } else {
      body.classList.remove('dark-mode');
      document.getElementById('mainNavbar').classList.remove('dark-mode');
      localStorage.setItem('darkMode', 'false');
    }
    updateDarkModeIcon();
  });

  // Keyboard accessible dark mode toggle (wrapper div)
  darkModeToggleWrapper.addEventListener('keydown', (e) => {
    if (e.key === ' ' || e.key === 'Enter') {
      e.preventDefault();
      darkModeToggle.checked = !darkModeToggle.checked;
      darkModeToggle.dispatchEvent(new Event('change'));
    }
  });

  function updateDarkModeIcon() {
    if (darkModeToggle.checked) {
      darkModeIcon.classList.remove('bi-moon-fill');
      darkModeIcon.classList.add('bi-sun-fill');
      darkModeIcon.setAttribute('title', 'Switch to light mode');
    } else {
      darkModeIcon.classList.remove('bi-sun-fill');
      darkModeIcon.classList.add('bi-moon-fill');
      darkModeIcon.setAttribute('title', 'Switch to dark mode');
    }
  }

  // Password visibility toggles in Edit Profile Modal
  const passwordInput = document.getElementById('passwordInput');
  const confirmPasswordInput = document.getElementById('confirmPasswordInput');
  const togglePasswordBtn = document.getElementById('togglePasswordBtn');
  const toggleConfirmPasswordBtn = document.getElementById('toggleConfirmPasswordBtn');
  const saveProfileBtn = document.getElementById('saveProfileBtn');
  const confirmPasswordHelp = document.getElementById('confirmPasswordHelp');
  const editProfileForm = document.getElementById('editProfileForm');

  togglePasswordBtn.addEventListener('click', () => {
    togglePasswordVisibility(passwordInput, togglePasswordBtn);
  });
  toggleConfirmPasswordBtn.addEventListener('click', () => {
    togglePasswordVisibility(confirmPasswordInput, toggleConfirmPasswordBtn);
  });

  function togglePasswordVisibility(input, btn) {
    if (input.type === 'password') {
      input.type = 'text';
      btn.innerHTML = '<i class="bi bi-eye-slash"></i>';
    } else {
      input.type = 'password';
      btn.innerHTML = '<i class="bi bi-eye"></i>';
    }
  }

  // Live validation for passwords and enabling save button
  function validateProfileForm() {
    // Username must be filled
    const usernameValid = !!document.getElementById('usernameInput').value.trim();

    // Passwords must match or both empty (to allow no password change)
    let passwordsMatch = true;
    if (passwordInput.value || confirmPasswordInput.value) {
      passwordsMatch = passwordInput.value === confirmPasswordInput.value;
    }

    // Show/hide error message
    if (!passwordsMatch) {
      confirmPasswordHelp.style.display = 'block';
      confirmPasswordInput.setCustomValidity('Passwords do not match.');
    } else {
      confirmPasswordHelp.style.display = 'none';
      confirmPasswordInput.setCustomValidity('');
    }

    // Enable save only if username is valid and passwords match
    saveProfileBtn.disabled = !(usernameValid && passwordsMatch);
  }

  passwordInput.addEventListener('input', validateProfileForm);
  confirmPasswordInput.addEventListener('input', validateProfileForm);
  document.getElementById('usernameInput').addEventListener('input', validateProfileForm);

  // Initial call
  validateProfileForm();


  
});
</script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    initCalendar('calendar', 'get_events.php', <?= isset($_SESSION['user']) ? $_SESSION['user']['id'] : 'null' ?>);
  });
</script>
<script>
  // Example: Simple client-side form validation
document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.querySelector('form[action="login.php"]');
    const registerForm = document.querySelector('form[action="register.php"]');

    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            const username = loginForm.querySelector('input[name="username"]').value.trim();
            if (!username) { e.preventDefault(); alert('Username required'); }
        });
    }

    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            const username = registerForm.querySelector('input[name="username"]').value.trim();
            const email = registerForm.querySelector('input[name="email"]').value.trim();
            const password = registerForm.querySelector('input[name="password"]').value.trim();
            if (!username || !email || !password) { e.preventDefault(); alert('All fields required'); }
        });
    }
});

</script>
<script>
  function initCalendar(elementId, eventsUrl, userId = null) {
  const calendarEl = document.getElementById(elementId);
  if (!calendarEl) return;

  const calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: "dayGridMonth",
    editable: !!userId,
    selectable: !!userId,
    selectMirror: true,
    dayMaxEvents: true,
    navLinks: true,
    nowIndicator: true,
    headerToolbar: {
      left: "prev,next today",
      center: "title",
      right: "dayGridMonth,timeGridWeek,timeGridDay,listWeek",
    },
    events: function (fetchInfo, successCallback, failureCallback) {
      fetch(`${eventsUrl}?user_id=${userId}`)
        .then((res) => res.json())
        .then((data) => successCallback(data))
        .catch((err) => failureCallback(err));
    },

    eventDrop: function (info) {
      updateEvent(info.event);
    },
    eventResize: function (info) {
      updateEvent(info.event);
    },

    eventClick: function (info) {
      if (!userId) return;
      const event = info.event;

      if (
        event.extendedProps.user_id &&
        event.extendedProps.user_id != userId
      ) {
        alert("You cannot edit this event.");
        return;
      }

      openEventModal(event);
    },

    select: function (info) {
      if (!userId) return;
      openEventModal(null, info);
    },

    eventDidMount: function (info) {
      if (!info.event.allDay) {
        info.el.style.whiteSpace = "nowrap";
      }
    },
  });

  calendar.render();

  // Modal handler
  function openEventModal(event = null, selection = null) {
    const modal = new bootstrap.Modal(document.getElementById("eventModal"));
    const form = document.getElementById("eventForm");
    const deleteBtn = document.getElementById("deleteEventBtn");

    form.reset();
    deleteBtn.style.display = "none";
    form.eventId.value = "";

    if (event) {
      form.eventId.value = event.id;
      form.eventTitle.value = event.title;
      form.eventStart.value = event.start
        ? event.start.toISOString().slice(0, 16)
        : "";
      form.eventEnd.value = event.end
        ? event.end.toISOString().slice(0, 16)
        : "";
      form.eventColor.value = event.backgroundColor || "#3788d8";
      deleteBtn.style.display = "inline-block";
    } else if (selection) {
      form.eventStart.value = selection.startStr.slice(0, 16);
      form.eventEnd.value = selection.endStr
        ? selection.endStr.slice(0, 16)
        : form.eventStart.value;
      form.eventColor.value = "#3788d8";
    }

    modal.show();
  }

  // Save event
  document.getElementById("eventForm").addEventListener("submit", function (e) {
    e.preventDefault();
    const formData = new FormData(this);
    const id = formData.get("id");
    const action = id ? "edit" : "add";

    formData.append("action", action);
    formData.append("user_id", userId);

    fetch("events.php", {
      method: "POST",
      body: formData,
    })
      .then((res) => res.json())
      .then((data) => {
        if (data.ok) {
          bootstrap.Modal.getInstance(
            document.getElementById("eventModal")
          ).hide();
          calendar.refetchEvents();
        } else {
          alert(data.msg || "Something went wrong.");
        }
      })
      .catch((err) => {
        console.error(err);
        alert("Failed to save event.");
      });
  });

  // Delete event
  document
    .getElementById("deleteEventBtn")
    .addEventListener("click", function () {
      const eventId = document.getElementById("eventId").value;
      if (!eventId || !confirm("Are you sure you want to delete this event?"))
        return;

      const formData = new FormData();
      formData.append("id", eventId);
      formData.append("action", "delete");
      formData.append("user_id", userId);

      fetch("events.php", {
        method: "POST",
        body: formData,
      })
        .then((res) => res.json())
        .then((data) => {
          if (data.ok) {
            bootstrap.Modal.getInstance(
              document.getElementById("eventModal")
            ).hide();
            calendar.refetchEvents();
          } else {
            alert(data.msg || "Failed to delete event.");
          }
        })
        .catch((err) => {
          console.error(err);
          alert("Error deleting event.");
        });
    });

  async function updateEvent(event) {
    const formData = new FormData();
    formData.append("id", event.id);
    formData.append("title", event.title);
    formData.append("start", event.start.toISOString());
    formData.append("end", event.end ? event.end.toISOString() : "");
    formData.append("color", event.backgroundColor || "#3788d8");
    formData.append("action", "edit");
    formData.append("user_id", userId);

    try {
      const res = await fetch("events.php", {
        method: "POST",
        body: formData,
      });
      const data = await res.json();
      if (!data.ok) {
        alert(data.msg || "Failed to update event.");
        calendar.refetchEvents();
      }
    } catch (err) {
      console.error(err);
      calendar.refetchEvents();
    }
  }

  return calendar;
}

</script>
<script>
  class IntervalNode {
    constructor(start, end, data) {
        this.start = start;
        this.end = end;
        this.max = end;
        this.data = data;
        this.left = null;
        this.right = null;
    }
}

class IntervalTree {
    constructor() {
        this.root = null;
    }

    insert(start, end, data) {
        this.root = this._insert(this.root, start, end, data);
    }

    _insert(node, start, end, data) {
        if (!node) return new IntervalNode(start, end, data);
        if (start < node.start) node.left = this._insert(node.left, start, end, data);
        else node.right = this._insert(node.right, start, end, data);
        node.max = Math.max(node.max, end);
        return node;
    }

    search(start, end) {
        const result = [];
        this._search(this.root, start, end, result);
        return result;
    }

    _search(node, start, end, result) {
        if (!node) return;
        if (node.start <= end && node.end >= start) result.push(node.data);
        if (node.left && node.left.max >= start) this._search(node.left, start, end, result);
        this._search(node.right, start, end, result);
    }
}

</script>
</body>
</html>
