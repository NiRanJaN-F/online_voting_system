<?php
session_start();
require 'config.php';
require 'mail_config.php';

if (!isset($_SESSION['pending_user']['email'])) {
    echo "Email not set in session.";
    exit;
}

$email = $_SESSION['pending_user']['email'];
$stmt = $conn->prepare("SELECT username FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    $username = $user['username'];
    $otp = rand(100000, 999999);
    $_SESSION['otp'] = $otp;

    if (sendOTP($email, $otp, $username)) {
        echo "OTP sent to $email";
    } else {
        echo "Failed to send OTP";
    }
} else {
    echo "User not found.";
}
?>
