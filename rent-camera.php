<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit();
}
require 'db.php';

if (isset($_GET['id'])) {
    $camera_id = $_GET['id'];
    $user_id = $_SESSION['id'];
    $rental_date = date('Y-m-d');
    $payment_method = $_POST['payment_method'];

    $stmt = $conn->prepare("INSERT INTO rentals (user_id, camera_id, rental_date, payment_method) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $user_id, $camera_id, $rental_date, $payment_method);
    $stmt->execute();

    $rental_id = $stmt->insert_id;

    header("Location: chat.php?rental_id=$rental_id");
}
?>