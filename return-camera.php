<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit();
}
require 'db.php';

if (isset($_GET['id'])) {
    $camera_id = $_GET['id'];
    $return_date = date('Y-m-d');

    $stmt = $conn->prepare("UPDATE rentals SET return_date = ?, status = 'returned' WHERE camera_id = ? AND user_id = ? AND status = 'approved'");
    $stmt->bind_param("sii", $return_date, $camera_id, $_SESSION['id']);
    $stmt->execute();

    $stmt = $conn->prepare("UPDATE cameras SET available = 1 WHERE id = ?");
    $stmt->bind_param("i", $camera_id);
    $stmt->execute();

    header("Location: dashboard-user.php");
}
?>