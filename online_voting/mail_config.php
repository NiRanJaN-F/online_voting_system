<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

function sendOTP($email, $otp, $username = "User") {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = ' replace with your Gmail';  // replace with your Gmail
        $mail->Password   = ' replace with your App Password';    // replace with your App Password
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        $mail->setFrom('replace with your Gmail', 'Online Voting System');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Your OTP for Online Voting';
        $mail->Body    = "Hi <strong>$username</strong>,<br><br>Your OTP for Online Voting is: <h2>$otp</h2><br>Do not share this OTP with anyone.";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
