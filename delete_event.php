<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
requireLogin();
if(!isAdmin()) die("Access denied");

if($_SERVER['REQUEST_METHOD']==='POST'){
    $id = (int)$_POST['id'];
    $stmt = $pdo->prepare("DELETE FROM events WHERE id=?");
    $stmt->execute([$id]);
    header('Location: manage_events.php');
}
