<?php
session_start();
include 'db.php'; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password for security
    $secret_key = $_POST['secret_key']; // Admin access key

    // Check if the secret key is correct (Predefined for security)
    if ($secret_key !== "admin123") { // Change this key to something secure
        $error = "Invalid secret key!";
    } else {
        // Check if username already exists
        $check_query = "SELECT * FROM admin WHERE username='$username'";
        $check_result = mysqli_query($conn, $check_query);

        if (mysqli_num_rows($check_result) > 0) {
            $error = "Username already exists!";
        } else {
            // Insert new admin into the database
            $query = "INSERT INTO admin (username, password) VALUES ('$username', '$password')";
            if (mysqli_query($conn, $query)) {
                $success = "Admin registered successfully! <a href='admin_login.php'>Login here</a>";
            } else {
                $error = "Error registering admin!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow mx-auto" style="max-width: 400px;">
        <div class="card-header bg-primary text-white text-center">
            <h4>Admin Registration</h4>
        </div>
        <div class="card-body">
            <?php if (isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>
            <?php if (isset($success)) { echo "<div class='alert alert-success'>$success</div>"; } ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Secret Key</label>
                    <input type="text" name="secret_key" class="form-control" required>
                    <small class="text-muted">Only authorized admins can register. Ask for the secret key.</small>
                </div>
                <button type="submit" class="btn btn-primary w-100">Register</button>
            </form>
            <div class="text-center mt-3">
                <a href="admin_login.php">Already an admin? Login here</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>
