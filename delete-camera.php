<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}
require 'db.php';

if (isset($_GET['id'])) {
    $camera_id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM cameras WHERE id = ?");
    $stmt->bind_param("i", $camera_id);
    $stmt->execute();

    header("Location: dashboard-admin.php");
}
?>