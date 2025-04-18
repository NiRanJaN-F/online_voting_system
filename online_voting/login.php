<?php
include 'config.php';
session_start();

$error = "";

// OTP verification step
if (isset($_POST['otp_submit'])) {
    $enteredOtp = $_POST['otp_input'];

    if (isset($_SESSION['otp']) && $_SESSION['otp'] == $enteredOtp) {
        $_SESSION['user_id'] = $_SESSION['pending_user']['id'];
        $_SESSION['username'] = $_SESSION['pending_user']['username'];

        unset($_SESSION['pending_user'], $_SESSION['otp']);

        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid OTP!";
    }
}

// Main login step
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['otp_submit'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = "All fields are required!";
    } else {
        $query = "SELECT * FROM users WHERE email='$email'";
        $result = $conn->query($query);

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                $_SESSION['pending_user'] = [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'email' => $email
                ];

                header("Location: login.php?otp=1");
                exit();
            } else {
                $error = "Invalid email or password!";
            }
        } else {
            $error = "User not found!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg p-4">
                    <h2 class="text-center mb-4">Login</h2>
                    
                    <?php if (!empty($error)) { ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php } ?>

                    <?php if (!isset($_GET['otp']) && !isset($_POST['otp_submit'])): ?>
                        <form name="loginForm" action="" method="POST">
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Login</button>
                        </form>
                        <p class="mt-3 text-center">Don't have an account? <a href="register.php">Register here</a></p>
                    <?php else: ?>
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label class="form-label">Enter OTP</label>
                                <input type="text" name="otp_input" class="form-control" required>
                            </div>
                            <button type="submit" name="otp_submit" class="btn btn-success w-100">Verify OTP</button>
                        </form>
                        <div class="text-center mt-3">
                            <button type="button" id="resendOtpBtn" class="btn btn-link">Didn't receive OTP? Click to resend</button>
                            <p id="resendMsg" class="text-success mt-2"></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const resendBtn = document.getElementById("resendOtpBtn");
    const msgBox = document.getElementById("resendMsg");

    if (resendBtn) {
        resendBtn.addEventListener("click", function () {
            fetch("send_otp.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: ""
            })
            .then(response => response.text())
            .then(data => {
                msgBox.innerText = data;
            })
            .catch(() => {
                msgBox.innerText = "Error sending OTP.";
            });
        });
    }

    <?php if (isset($_GET['otp'])): ?>
    fetch("send_otp.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: ""
    });
    <?php endif; ?>
});
</script>

</body>
</html>
