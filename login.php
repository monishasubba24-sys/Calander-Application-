<?php
require_once '../includes/db.php';
require_once '../includes/auth.php'; // ← add this to use the shared functions
$msg = '';

// If already logged in, redirect appropriately
if (isLoggedIn()) {
    if (isAdmin()) {
        header("Location: ../admin/index.php");
    } else {
        header("Location: index.php");
    }
    exit;
}

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (authenticateUser($username, $password)) {
        // Redirect based on role
        if (isAdmin()) {
            header("Location: ../admin/index.php");
        } else {
            header("Location: index.php");
        }
        exit;
    } else {
        $msg = "Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

  <style>
    body {
      background: url('https://images.wallpapersden.com/image/download/purple-night-sky-hd-moon_bmdlaWWUmZqaraWkpJRmbmdlrWZlbWU.jpg') no-repeat center center/cover;
      height: 100vh;
      margin: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Segoe UI', sans-serif;
    }

    .glass-card {
      background: rgba(255, 255, 255, 0.1);
      border-radius: 15px;
      padding: 2rem;
      box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      border: 1px solid rgba(255, 255, 255, 0.2);
      color: white;
      width: 100%;
      max-width: 400px;
    }

    .form-control, .form-control:focus {
      background: rgba(255, 255, 255, 0.15);
      border: none;
      color: white;
    }

    .form-control::placeholder {
      color: #ccc;
    }

   

    .btn-outline-secondary, .btn-outline-dark {
      border-color: rgba(255, 255, 255, 0.4);
      color: white;
    }

    .btn-outline-secondary:hover, .btn-outline-dark:hover {
      background-color: rgba(255, 255, 255, 0.2);
    }

    .form-label {
      color: #eee;
      font-weight: 500;
    }

    .login-icon {
      font-size: 3rem;
      color: white;
    }

    .alert {
      background: rgba(255, 0, 0, 0.2);
      color: #ffdddd;
      border: none;
    }

    a.btn-link {
      color: #ccc;
    }

    a.btn-link:hover {
      color: white;
    }
  </style>
</head>
<body>

<div class="glass-card text-white">
  <div class="text-center mb-3">
    <i class="fas fa-user-circle login-icon"></i>
    <h3 class="mt-2 fw-bold">User Login</h3>
  </div>

  <?php if ($msg): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($msg) ?></div>
  <?php endif; ?>

  <form method="post" novalidate>
    <div class="mb-3">
      <label class="form-label"><i class="fas fa-user me-1"></i> Username</label>
      <input type="text" name="username" class="form-control" placeholder="Enter your username" required>
    </div>
    <div class="mb-3">
      <label class="form-label"><i class="fas fa-lock me-1"></i> Password</label>
      <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
    </div>

    <button type="submit" class="btn btn-success w-100">
      <i class="fas fa-sign-in-alt me-1"></i> Login
    </button>

    <div class="text-center mt-3">
      <a href="register.php" class="btn btn-outline-secondary btn-sm w-100 mb-2">
        <i class="fas fa-user-plus me-1"></i> Register
      </a>
    </div>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
