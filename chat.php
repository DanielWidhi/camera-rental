<?php
session_start();
require 'db.php';

if (!isset($_SESSION['role'])) {
    header("Location: login.php");
    exit();
}

$rental_id = $_GET['rental_id'];
$user_id = $_SESSION['id'];
$role = $_SESSION['role'];

// Fetch rental details
$stmt = $conn->prepare("SELECT * FROM rentals WHERE id = ?");
$stmt->bind_param("i", $rental_id);
$stmt->execute();
$rental = $stmt->get_result()->fetch_assoc();

// Fetch camera details
$stmt = $conn->prepare("SELECT * FROM cameras WHERE id = ?");
$stmt->bind_param("i", $rental['camera_id']);
$stmt->execute();
$camera = $stmt->get_result()->fetch_assoc();

// Fetch messages
$messages = $conn->query("SELECT * FROM messages WHERE rental_id = $rental_id ORDER BY created_at ASC");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['message'])) {
    $message = $_POST['message'];
    $stmt = $conn->prepare("INSERT INTO messages (rental_id, user_id, message) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $rental_id, $user_id, $message);
    $stmt->execute();
    header("Location: chat.php?rental_id=$rental_id");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Chat</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Chat</h1>
    <div class="chat-box">
        <?php if ($rental['status'] == 'rejected'): ?>
            <p>Status: Tidak Disetujui</p>
        <?php endif; ?>
        <?php while ($msg = $messages->fetch_assoc()): ?>
            <div class="message">
                <?php if ($role == 'admin'): ?>
                    <strong><?php echo $msg['user_id'] == $user_id ? 'Anda' : 'User'; ?>:</strong>
                <?php else: ?>
                    <strong><?php echo $msg['user_id'] == $user_id ? 'Anda' : 'Admin'; ?>:</strong>
                <?php endif; ?>
                <p><?php echo $msg['message']; ?></p>
            </div>
        <?php endwhile; ?>
    </div>
    <form method="POST" action="chat.php?rental_id=<?php echo $rental_id; ?>">
        <textarea name="message" required></textarea>
        <button type="submit">Kirim</button>
    </form>
    <?php if ($role == 'admin' && $rental['status'] == 'pending'): ?>
        <form method="POST" action="process-rental.php">
            <input type="hidden" name="rental_id" value="<?php echo $rental_id; ?>">
            <input type="hidden" name="camera_id" value="<?php echo $camera['id']; ?>">
            <button type="submit" name="action" value="approve">Setuju</button>
            <button type="submit" name="action" value="reject">Tidak Setuju</button>
        </form>
    <?php endif; ?>
    <a href="dashboard-<?php echo $role; ?>.php">Back</a>
</body>
</html>