<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
requireLogin();
if (!isAdmin()) die("Access denied");

$id       = $_POST['id'] ?? null;
$title    = $_POST['title'] ?? '';
$start    = $_POST['start'] ?? '';
$end      = $_POST['end'] ?? '';
$user_id  = $_POST['user_id'] ?? '';
$color    = $_POST['color'] ?? '#3788d8';

if ($id) {
    // Update
    $stmt = $pdo->prepare("UPDATE events SET title=?, start_time=?, end_time=?, user_id=?, color=? WHERE id=?");
    $stmt->execute([$title, $start, $end, $user_id, $color, $id]);
} else {
    // Insert
    $stmt = $pdo->prepare("INSERT INTO events (title, start_time, end_time, user_id, color) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$title, $start, $end, $user_id, $color]);
}

header('Location: manage_events.php');
exit;
