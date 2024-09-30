<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}
require 'db.php';

if (isset($_GET['id'])) {
    $camera_id = $_GET['id'];
    $camera = $conn->query("SELECT * FROM cameras WHERE id = $camera_id")->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $camera_id = $_POST['id'];
    $name = $_POST['name'];
    $brand = $_POST['brand'];
    $model = $_POST['model'];
    $specifications = $_POST['specifications'];
    $kondisi = $_POST['kondisi'];
    $price = $_POST['price'];
    $image = $_FILES['image']['name'];
    $target = "image/" . basename($image);

    if ($image) {
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
        $stmt = $conn->prepare("UPDATE cameras SET name = ?, brand = ?, model = ?, specifications = ?, kondisi = ?, price = ?, image = ? WHERE id = ?");
        $stmt->bind_param("sssssssi", $name, $brand, $model, $specifications, $kondisi, $price, $target, $camera_id);
    } else {
        $stmt = $conn->prepare("UPDATE cameras SET name = ?, brand = ?, model = ?, specifications = ?, kondisi = ?, price = ? WHERE id = ?");
        $stmt->bind_param("ssssssi", $name, $brand, $model, $specifications, $kondisi, $price, $camera_id);
    }
    $stmt->execute();

    header("Location: dashboard-admin.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Camera</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <form method="POST" action="edit-camera.php" enctype="multipart/form-data">
        <h2>Edit Camera</h2>
        <input type="hidden" name="id" value="<?php echo $camera['id']; ?>">
        <label for="name">Name</label>
        <input type="text" name="name" value="<?php echo $camera['name']; ?>" required>
        <label for="brand">Brand</label>
        <input type="text" name="brand" value="<?php echo $camera['brand']; ?>" required>
        <label for="model">Model</label>
        <input type="text" name="model" value="<?php echo $camera['model']; ?>" required>
        <label for="specifications">Specifications</label>
        <textarea name="specifications" required><?php echo $camera['specifications']; ?></textarea>
        <label for="kondisi">Condition</label>
        <textarea name="kondisi" required><?php echo $camera['kondisi']; ?></textarea>
        <label for="price">Price</label>
        <input type="text" name="price" value="<?php echo $camera['price']; ?>" required>
        <label for="image">Image</label>
        <input type="file" name="image">
        <button type="submit">Update Camera</button>
    </form>
</body>
</html>