<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
require_once '../includes/helpers.php';

requireLogin();
$user_id = $_SESSION['user']['id'];
$action = $_POST['action'] ?? '';

if ($action === 'add') {
    $title = sanitize($_POST['title']);
    $start = $_POST['start'];
    $end = $_POST['end'];
    $color = $_POST['color'] ?? '#3788d8';

    $stmt = $pdo->prepare("INSERT INTO events (user_id, title, start_time, end_time, color) VALUES (?,?,?,?,?)");
    $stmt->execute([$user_id, $title, $start, $end, $color]);
    jsonResponse(true, "Event added");

} elseif ($action === 'edit') {
    $id = (int)$_POST['id'];
    $stmt = $pdo->prepare("SELECT * FROM events WHERE id=? AND user_id=?");
    $stmt->execute([$id, $user_id]);
    $event = $stmt->fetch();
    if (!$event) jsonResponse(false, "Unauthorized");

    $title = sanitize($_POST['title']);
    $start = $_POST['start'];
    $end = $_POST['end'];
    $color = $_POST['color'] ?? '#3788d8';

    $stmt = $pdo->prepare("UPDATE events SET title=?, start_time=?, end_time=?, color=? WHERE id=?");
    $stmt->execute([$title, $start, $end, $color, $id]);
    jsonResponse(true, "Event updated");

} elseif ($action === 'delete') {
    $id = (int)$_POST['id'];
    $stmt = $pdo->prepare("DELETE FROM events WHERE id=? AND user_id=?");
    $stmt->execute([$id, $user_id]);
    jsonResponse(true, "Event deleted");
} else {
    jsonResponse(false, "Invalid action");
}
