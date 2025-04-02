<?php
session_start();
include 'db.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$sql = "UPDATE candidates SET votes = 0";
if (mysqli_query($conn, $sql)) {
    echo "<script>alert('All votes have been reset!'); window.location='admin_dashboard.php';</script>";
} else {
    echo "<script>alert('Error resetting votes'); window.location='admin_dashboard.php';</script>";
}
?>
