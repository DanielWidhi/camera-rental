<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $owner_id = $_SESSION['id'];
    $name = $_POST['name'];
    $brand = $_POST['brand'];
    $model = $_POST['model'];
    $specifications = $_POST['specifications'];
    $kondisi = $_POST['kondisi'];
    $price = $_POST['price'];
    $image = $_FILES['image']['name'];
    $target = "image/" . basename($image);

    move_uploaded_file($_FILES['image']['tmp_name'], $target);

    $stmt = $conn->prepare("INSERT INTO cameras (owner_id, name, brand, model, specifications, kondisi, price, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssssss", $owner_id, $name, $brand, $model, $specifications, $kondisi, $price, $target);
    $stmt->execute();

    header("Location: dashboard-admin.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Camera</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <form method="POST" action="add-camera.php" enctype="multipart/form-data">
        <h2>Add Camera</h2>
        <label for="name">Name</label>
        <input type="text" name="name" required>
        <label for="brand">Brand</label>
        <input type="text" name="brand" required>
        <label for="model">Model</label>
        <input type="text" name="model" required>
        <label for="specifications">Specifications</label>
        <textarea name="specifications" required></textarea>
        <label for="kondisi">Condition</label>
        <textarea name="kondisi" required></textarea>
        <label for="price">Price</label>
        <input type="text" name="price" required>
        <label for="image">Image</label>
        <input type="file" name="image" required>
        <button type="submit">Add Camera</button>
    </form>
</body>
</html>