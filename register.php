<?php
require_once '../includes/db.php';
session_start();
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (username,email,password) VALUES (?,?,?)");
    try {
        $stmt->execute([$username, $email, $password]);

        // Fetch the newly inserted user
        $user_id = $pdo->lastInsertId();
        $stmt = $pdo->prepare("SELECT id, username, email FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Store user data in session
        $_SESSION['user'] = $user;

        // Redirect to index after successful registration
        header("Location: index.php");
        exit;

    } catch (Exception $e) {
        // Only set error message if something went wrong (e.g., duplicate username/email)
        $msg = "Username or email already exists.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      background: url('https://th.bing.com/th/id/OIP.IO-oAXD09HJ0lEY3WMmd3AHaNJ?w=178&h=287&c=7&r=0&o=7&cb=12&pid=1.7&rm=3') no-repeat center center / cover;
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
      box-shadow: 0 8px 32px rgba(31, 38, 135, 0.37);
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

    .btn-success {
      background-color: #28a745;
      border: none;
    }

    .form-label {
      color: #eee;
      font-weight: 500;
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

    .register-icon {
      font-size: 3rem;
      color: white;
    }
  </style>
</head>
<body>

<div class="glass-card text-white">
  <div class="text-center mb-3">
    <i class="fas fa-user-plus register-icon"></i>
    <h3 class="mt-2 fw-bold">Create Account</h3>
  </div>

  <?php if ($msg): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($msg) ?></div>
  <?php endif; ?>

  <form method="post">
    <div class="mb-3">
      <label class="form-label"><i class="fas fa-user me-1"></i> Username</label>
      <input type="text" name="username" class="form-control" placeholder="Choose a username" required>
    </div>

    <div class="mb-3">
      <label class="form-label"><i class="fas fa-envelope me-1"></i> Email</label>
      <input type="email" name="email" class="form-control" placeholder="Your email address" required>
    </div>

    <div class="mb-3">
      <label class="form-label"><i class="fas fa-lock me-1"></i> Password</label>
      <input type="password" name="password" class="form-control" placeholder="Create a password" required>
    </div>

    <button class="btn btn-success w-100"><i class="fas fa-user-plus me-1"></i> Register</button>

    <div class="text-center mt-3">
      <a href="login.php" class="btn btn-link w-100">Already have an account? Login</a>
    </div>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
