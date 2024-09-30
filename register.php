<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = $_POST['role'];
    $name = $_POST['name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];

    $stmt = $conn->prepare("INSERT INTO users (username, password, role, name, address, phone, email) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $username, $password, $role, $name, $address, $phone, $email);
    $stmt->execute();

    header("Location: login.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <form method="POST" action="register.php">
        <h2>Register</h2>
        <label for="username">Username</label>
        <input type="text" name="username" required>
        <label for="password">Password</label>
        <input type="password" name="password" required>
        <label for="role">Role</label>
        <select name="role">
            <option value="user">User</option>
            <option value="admin">Admin</option>
        </select>
        <label for="name">Name</label>
        <input type="text" name="name" required>
        <label for="address">Address</label>
        <textarea name="address" required></textarea>
        <label for="phone">Phone</label>
        <input type="text" name="phone" required>
        <label for="email">Email</label>
        <input type="email" name="email" required>
        <button type="submit">Register</button>
    </form>
</body>
</html>