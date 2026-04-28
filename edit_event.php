<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
require_once '../includes/helpers.php';

requireLogin();
$user_id = $_SESSION['user']['id'];

if($_SERVER['REQUEST_METHOD']==='POST'){
    $id = (int)$_POST['id'];
    $title = sanitize($_POST['title']);
    $start = $_POST['start'];
    $end = $_POST['end'];
    $color = $_POST['color'] ?? '#3788d8';

    // Check ownership
    $stmt = $pdo->prepare("SELECT * FROM events WHERE id=? AND user_id=?");
    $stmt->execute([$id,$user_id]);
    $event = $stmt->fetch();
    if(!$event){
        jsonResponse(false,"Unauthorized access");
    }

    $stmt = $pdo->prepare("UPDATE events SET title=?, start_time=?, end_time=?, color=? WHERE id=?");
    $stmt->execute([$title,$start,$end,$color,$id]);

    jsonResponse(true,"Event updated");
}
