<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}
require 'db.php';

$rentals = $conn->query("SELECT rentals.*, users.username, cameras.name AS camera_name FROM rentals JOIN users ON rentals.user_id = users.id JOIN cameras ON rentals.camera_id = cameras.id WHERE rentals.status = 'pending'");
$cameras = $conn->query("SELECT * FROM cameras WHERE owner_id = " . $_SESSION['id']);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Welcome, <?php echo $_SESSION['username']; ?></h1>
    <a href="logout.php">Logout</a>
    <h2>Pending Rentals</h2>
    <ul>
        <?php while ($rental = $rentals->fetch_assoc()): ?>
            <li>
                <?php echo $rental['username']; ?> wants to rent <?php echo $rental['camera_name']; ?>
                <a href="chat.php?rental_id=<?php echo $rental['id']; ?>">View Chat</a>
                <a href="process-return.php?id=<?php echo $rental['id']; ?>">Process Return</a>
            </li>
        <?php endwhile; ?>
    </ul>
    <h2>Manajemen Kamera</h2>
    
    <ul>
        <?php while ($camera = $cameras->fetch_assoc()): ?>
            <li>
                <img src="<?php echo $camera['image']; ?>" alt="<?php echo $camera['name']; ?>" style="width:500px;height:auto;">
                <?php echo $camera['name']; ?> - <?php echo $camera['available'] ? 'Available' : 'Not Available'; ?>
                <a href="edit-camera.php?id=<?php echo $camera['id']; ?>">Edit</a>
                <a href="delete-camera.php?id=<?php echo $camera['id']; ?>">Delete</a>
            </li>
        <?php endwhile; ?>
    </ul>
    <label for="tambah__kamera" class="kamera">
        <div>
            Tambah Kamera
        </div>
        <div>
            <a href="add-camera.php">+</a>
        </div>
    </label>
</body>
</html>