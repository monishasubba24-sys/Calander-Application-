<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
requireLogin();
if (!isAdmin()) die("Access denied");

$id = $_POST['id'] ?? null;

if ($id) {
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);
}

header("Location: manage_users.php");
exit;
