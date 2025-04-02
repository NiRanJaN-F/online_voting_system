<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $voter_id = $_POST['voter_id'];
    $aadhaar_name = $_POST['aadhaar_name'];
    $age = $_POST['age'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Verify Voter ID, Name, and Age in voter_list table
    $verify_query = "SELECT * FROM voter_list WHERE voter_id='$voter_id' AND name='$aadhaar_name' AND age='$age'";
    $verify_result = $conn->query($verify_query);

    if ($verify_result->num_rows == 0) {
        $error = "Voter ID, Name, or Age does not match our records!";
    } else {
        // Check if user already exists
        $check_query = "SELECT * FROM users WHERE email='$email' OR username='$username'";
        $result = $conn->query($check_query);

        if ($result->num_rows > 0) {
            $error = "Username or Email already exists!";
        } else {
            // Insert into users table
            $sql = "INSERT INTO users (voter_id, aadhaar_name, age, username, email, password) 
                    VALUES ('$voter_id', '$aadhaar_name', '$age', '$username', '$email', '$password')";
            if ($conn->query($sql) === TRUE) {
                header("Location: login.php?registered=success");
                exit();
            } else {
                $error = "Error: " . $conn->error;
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
    <title>User Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg p-4">
                    <h2 class="text-center mb-4">Register</h2>
                    <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
                    <form action="" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Voter ID</label>
                            <input type="text" name="voter_id" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Name (As in Aadhaar)</label>
                            <input type="text" name="aadhaar_name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Age</label>
                            <input type="number" name="age" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Register</button>
                    </form>
                    <p class="mt-3 text-center">Already have an account? <a href="login.php">Login here</a></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
