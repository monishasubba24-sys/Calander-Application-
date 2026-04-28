<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
requireLogin();
if(!isAdmin()) die("Access denied");

$users = $pdo->query("SELECT * FROM users ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Users</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

  <style>
    body {
      background-color: #f8f9fa;
    }
    .card {
      border: none;
    }
    .table th {
      background-color: #343a40;
      color: white;
    }
    .table-hover tbody tr:hover {
      background-color: #f1f1f1;
    }
    .modal-title i {
      margin-right: 8px;
    }
  </style>
</head>
<body>

<div class="container mt-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-users-cog"></i> Manage Users</h2>
    <button class="btn btn-success" onclick="openAddUserModal()"><i class="fas fa-user-plus"></i> Add User</button>
  </div>

  <div class="card shadow-sm p-3">
    <div class="table-responsive">
      <table class="table table-striped table-hover align-middle">
        <thead class="table-dark">
          <tr>
            <th>#</th> <!-- Serial Number Column -->
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th class="text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
        <?php 
          $serial = 1;  // Initialize serial number counter
          foreach($users as $u): 
        ?>
          <tr>
            <td><?= $serial++ ?></td> <!-- Serial Number -->
            <td><?= $u['id'] ?></td>
            <td><?= htmlspecialchars($u['username']) ?></td>
            <td><?= htmlspecialchars($u['email']) ?></td>
            <td>
              <span class="badge bg-<?= $u['role'] === 'admin' ? 'danger' : 'secondary' ?>">
                <?= ucfirst($u['role']) ?>
              </span>
            </td>
            <td class="text-center">
              <button class="btn btn-warning btn-sm me-1" onclick='openEditUserModal(<?= json_encode($u) ?>)'>
                <i class="fas fa-edit"></i>
              </button>
              <form action="delete_user.php" method="POST" class="d-inline">
                <input type="hidden" name="id" value="<?= $u['id'] ?>">
                <button class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?')">
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

<!-- User Modal -->
<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" action="save_user.php" method="POST">
      <div class="modal-header">
        <h5 class="modal-title" id="userModalTitle"><i class="fas fa-user"></i> User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="id" id="userId">

        <div class="mb-3">
          <label class="form-label">Username</label>
          <input type="text" name="username" id="username" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="email" name="email" id="email" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Password</label>
          <input type="password" name="password" id="password" class="form-control">
          <div class="form-text">Leave blank to keep the existing password.</div>
        </div>

        <div class="mb-3">
          <label class="form-label">Role</label>
          <select name="role" id="role" class="form-select" required>
            <option value="user">User</option>
            <option value="admin">Admin</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary" type="submit"><i class="fas fa-save"></i> Save</button>
        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
function openAddUserModal() {
    document.getElementById('userModalTitle').innerHTML = '<i class="fas fa-user-plus"></i> Add User';
    document.getElementById('userId').value = '';
    document.getElementById('username').value = '';
    document.getElementById('email').value = '';
    document.getElementById('password').value = '';
    document.getElementById('role').value = 'user';
    new bootstrap.Modal(document.getElementById('userModal')).show();
}

function openEditUserModal(user) {
    document.getElementById('userModalTitle').innerHTML = '<i class="fas fa-user-edit"></i> Edit User';
    document.getElementById('userId').value = user.id;
    document.getElementById('username').value = user.username;
    document.getElementById('email').value = user.email;
    document.getElementById('password').value = '';
    document.getElementById('role').value = user.role;
    new bootstrap.Modal(document.getElementById('userModal')).show();
}
</script>

</body>
</html>
