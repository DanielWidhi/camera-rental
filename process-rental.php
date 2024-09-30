<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}
require 'db.php';

if (isset($_POST['rental_id']) && isset($_POST['action'])) {
    $rental_id = $_POST['rental_id'];
    $camera_id = $_POST['camera_id'];
    $action = $_POST['action'];

    if ($action == 'approve') {
        $stmt = $conn->prepare("UPDATE rentals SET status = 'approved' WHERE id = ?");
        $stmt->bind_param("i", $rental_id);
        $stmt->execute();

        $stmt = $conn->prepare("UPDATE cameras SET available = 0 WHERE id = ?");
        $stmt->bind_param("i", $camera_id);
        $stmt->execute();

        header("Location: dashboard-admin.php");
    } else {
        $stmt = $conn->prepare("UPDATE rentals SET status = 'rejected' WHERE id = ?");
        $stmt->bind_param("i", $rental_id);
        $stmt->execute();

        header("Location: chat.php?rental_id=$rental_id&status=rejected");
    }
}
?>