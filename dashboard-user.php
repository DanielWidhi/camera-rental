<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit();
}
require 'db.php';

$user_id = $_SESSION['id'];
$cameras = $conn->query("SELECT * FROM cameras WHERE available = 1");
$rented_cameras = $conn->query("SELECT cameras.* FROM rentals JOIN cameras ON rentals.camera_id = cameras.id WHERE rentals.user_id = $user_id AND rentals.status = 'approved'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Welcome, <?php echo $_SESSION['username']; ?></h1>
    <a href="logout.php">Logout</a>
    
    <h2>Available Cameras</h2>
    <ul>
        <?php while ($camera = $cameras->fetch_assoc()): ?>
            <li>
            <img src="<?php echo $camera['image']; ?>" alt="<?php echo $camera['name']; ?>" style="width:500px;height:auto;">
                <?php echo $camera['name']; ?>
                <a href="rent-camera.php?id=<?php echo $camera['id']; ?>">Rent</a>
            </li>
        <?php endwhile; ?>
    </ul>

    <h2>Rented Cameras</h2>
    <ul>
        <?php while ($camera = $rented_cameras->fetch_assoc()): ?>
            <li>
                <?php echo $camera['name']; ?>
                <a href="return-camera.php?id=<?php echo $camera['id']; ?>">Return</a>
            </li>
        <?php endwhile; ?>
    </ul>
</body>
</html>