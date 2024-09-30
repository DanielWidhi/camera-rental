<?php
session_start();
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'admin') {
        header("Location: dashboard-admin.php");
    } else {
        header("Location: dashboard-user.php");
    }
} else {
    header("Location: login.php");
}
?>