<?php
session_start();
include 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$error = "";
$success = "";

// Check if user has already voted
$checkVote = "SELECT * FROM votes WHERE user_id='$user_id'";
$result = $conn->query($checkVote);

if ($result->num_rows > 0) {
    $error = "You have already voted! Only one vote per user is allowed.";
}

// Fetch candidates from database
$candidatesQuery = "SELECT * FROM candidates";
$candidatesResult = $conn->query($candidatesQuery);

// Handle Vote Submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && empty($error)) {
    $candidate_id = $_POST['candidate'];

    if (empty($candidate_id)) {
        $error = "Please select a candidate!";
    } else {
        // Update votes count in candidates table
        $voteQuery = "UPDATE candidates SET votes = votes + 1 WHERE id = '$candidate_id'";
        if ($conn->query($voteQuery) === TRUE) {
            // Insert record into votes table to track user voting
            $insertVote = "INSERT INTO votes (user_id, candidate_id) VALUES ('$user_id', '$candidate_id')";
            $conn->query($insertVote);

            $success = "Vote submitted successfully!";
        } else {
            $error = "Error submitting vote: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vote Now</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg p-4">
                    <h2 class="text-center mb-4">Vote for Your Candidate</h2>

                    <?php if (!empty($error)) { ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php } ?>

                    <?php if (!empty($success)) { ?>
                        <div class="alert alert-success"><?php echo $success; ?></div>
                    <?php } ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Select Candidate</label>
                            <select name="candidate" class="form-control" required>
                                <option value="">-- Choose --</option>
                                <?php while ($row = $candidatesResult->fetch_assoc()) { ?>
                                    <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Submit Vote</button>
                    </form>

                    <p class="mt-3 text-center">
                        <a href="dashboard.php">Back to Dashboard</a> | 
                        <a href="logout.php" class="text-danger">Logout</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
