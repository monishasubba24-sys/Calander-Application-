<?php
require_once '../includes/db.php';
header('Content-Type: application/json');

$stmt = $pdo->query("
    SELECT e.id, e.title, e.start_time AS start, e.end_time AS end, e.color, u.id AS user_id, u.username
    FROM events e 
    JOIN users u ON e.user_id = u.id
");
$events = $stmt->fetchAll();

echo json_encode($events);
