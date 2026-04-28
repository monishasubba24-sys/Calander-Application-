<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
requireLogin();
if (!isAdmin()) die("Access denied");

$id = $_POST['id'] ?? null;
$username = $_POST['username'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$role = $_POST['role'] ?? 'user';

if ($id) {
    // Update user
    if ($password) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET username=?, email=?, password=?, role=? WHERE id=?");
        $stmt->execute([$username, $email, $hashed, $role, $id]);
    } else {
        $stmt = $pdo->prepare("UPDATE users SET username=?, email=?, role=? WHERE id=?");
        $stmt->execute([$username, $email, $role, $id]);
    }
} else {
    // Add new user
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->execute([$username, $email, $hashed, $role]);
}

header("Location: manage_users.php");
exit;
