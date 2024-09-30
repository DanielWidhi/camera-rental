<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}
require 'db.php';

if (isset($_GET['id'])) {
    $rental_id = $_GET['id'];

    $stmt = $conn->prepare("UPDATE rentals SET status = 'returned' WHERE id = ?");
    $stmt->bind_param("i", $rental_id);
    $stmt->execute();

    header("Location: dashboard-admin.php");
}
?>