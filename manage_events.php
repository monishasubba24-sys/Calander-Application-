<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
requireLogin();
if (!isAdmin()) die("Access denied");

$events = $pdo->query("SELECT e.*, u.username FROM events e JOIN users u ON e.user_id = u.id ORDER BY start_time DESC")->fetchAll();
$users = $pdo->query("SELECT id, username FROM users")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Events</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

  <style>
    body {
      background-color: #f8f9fa;
    }
    .table th {
      background-color: #343a40;
      color: white;
    }
    .table-hover tbody tr:hover {
      background-color: #f1f1f1;
    }
    .badge-color {
      width: 24px;
      height: 24px;
      display: inline-block;
      border-radius: 50%;
      border: 1px solid #ccc;
    }
  </style>
</head>
<body>

<div class="container mt-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fa-solid fa-calendar-days me-2"></i>Manage Events</h2>
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#eventModal" onclick="openAddModal()">
      <i class="fa fa-plus-circle"></i> Add Event
    </button>
  </div>

  <div class="card shadow-sm p-3">
    <div class="table-responsive">
      <table class="table table-hover align-middle">
        <thead class="table-dark">
          <tr>
            <th>#</th>  <!-- Serial Number Header -->
            <th>ID</th>
            <th>Title</th>
            <th>User</th>
            <th>Start</th>
            <th>End</th>
            <th>Color</th>
            <th class="text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php 
            $serial = 1; // Initialize serial number counter
            foreach ($events as $e): 
          ?>
            <tr>
              <td><?= $serial++ ?></td> <!-- Serial Number -->
              <td><?= $e['id'] ?></td>
              <td><?= htmlspecialchars($e['title']) ?></td>
              <td><?= htmlspecialchars($e['username']) ?></td>
              <td><?= date("Y-m-d H:i", strtotime($e['start_time'])) ?></td>
              <td><?= date("Y-m-d H:i", strtotime($e['end_time'])) ?></td>
              <td><span class="badge-color" style="background-color: <?= $e['color'] ?>"></span></td>
              <td class="text-center">
                <button class="btn btn-warning btn-sm me-1" onclick='openEditModal(<?= json_encode($e) ?>)'>
                  <i class="fas fa-edit"></i>
                </button>
                <form method="post" action="delete_event.php" class="d-inline">
                  <input type="hidden" name="id" value="<?= $e['id'] ?>">
                  <button class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this event?')">
                    <i class="fas fa-trash-alt"></i>
                  </button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <a href="index.php" class="btn btn-secondary mt-3"><i class="fas fa-arrow-left"></i> Back</a>
  </div>
</div>

<!-- Event Modal -->
<div class="modal fade" id="eventModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" method="POST" action="add_event.php">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle"><i class="fas fa-calendar-plus me-1"></i> Add Event</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="id" id="eventId">

        <div class="mb-3">
          <label class="form-label">Title</label>
          <input type="text" class="form-control" name="title" id="eventTitle" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Start Time</label>
          <input type="datetime-local" class="form-control" name="start" id="eventStart" required>
        </div>

        <div class="mb-3">
          <label class="form-label">End Time</label>
          <input type="datetime-local" class="form-control" name="end" id="eventEnd" required>
        </div>

        <div class="mb-3">
          <label class="form-label">User</label>
          <select name="user_id" class="form-select" id="eventUser" required>
            <?php foreach ($users as $u): ?>
              <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['username']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Color</label>
          <input type="color" class="form-control form-control-color" name="color" id="eventColor" value="#3788d8">
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
    </form>
  </div>
</div>

<!-- Bootstrap + Script -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
function openAddModal() {
    document.getElementById('modalTitle').innerHTML = '<i class="fas fa-calendar-plus me-1"></i> Add Event';
    document.getElementById('eventId').value = '';
    document.getElementById('eventTitle').value = '';
    document.getElementById('eventStart').value = '';
    document.getElementById('eventEnd').value = '';
    document.getElementById('eventColor').value = '#3788d8';
    document.getElementById('eventUser').selectedIndex = 0;
}

function openEditModal(event) {
    document.getElementById('modalTitle').innerHTML = '<i class="fas fa-calendar-edit me-1"></i> Edit Event';
    document.getElementById('eventId').value = event.id;
    document.getElementById('eventTitle').value = event.title;
    document.getElementById('eventStart').value = event.start_time.replace(' ', 'T');
    document.getElementById('eventEnd').value = event.end_time.replace(' ', 'T');
    document.getElementById('eventColor').value = event.color;
    document.getElementById('eventUser').value = event.user_id;

    new bootstrap.Modal(document.getElementById('eventModal')).show();
}
</script>
</body>
</html>
