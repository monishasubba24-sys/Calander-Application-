<?php
session_start();
require_once '../includes/db.php'; // Make sure $pdo is a PDO instance

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user']['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    // Validate email and username
   
    if (strlen($username) < 3) {
        die("Username must be at least 3 characters.");
    }

    // Check if username or email already exists for a different user
    $stmt = $pdo->prepare("SELECT id FROM users WHERE (username = :username) AND id != :id LIMIT 1");
    $stmt->execute([
        ':username' => $username,
        ':id' => $userId,
    ]);
    if ($stmt->fetch(PDO::FETCH_ASSOC)) {
        die("Username or email already taken.");
    }

    // Validate passwords match if password fields are filled
    if (!empty($password) || !empty($confirmPassword)) {
        if ($password !== $confirmPassword) {
            die("Passwords do not match.");
        }
    }

    // Prepare update statement dynamically
    $sql = "UPDATE users SET  username = :username";
    $params = [
        
        ':username' => $username,
        ':id' => $userId,
    ];

    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql .= ", password = :password";
        $params[':password'] = $hashedPassword;
    }

    $sql .= " WHERE id = :id";

    $stmt = $pdo->prepare($sql);

    if ($stmt->execute($params)) {
        // Update session data
        $_SESSION['user']['username'] = $username;

        header('Location: index.php');
        exit;
    } else {
        die("Failed to update profile.");
    }
} else {
    header('Location: index.php');
    exit;
}
