<?php
session_start();
include 'db.php'; 

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Handle candidate deletion safely
if (isset($_GET['delete_candidate'])) {
    $candidate_id = $_GET['delete_candidate'];
    
    // Delete votes related to this candidate first
    $delete_votes = "DELETE FROM votes WHERE candidate_id = '$candidate_id'";
    mysqli_query($conn, $delete_votes);
    
    // Now delete the candidate
    $delete_candidate = "DELETE FROM candidates WHERE id = '$candidate_id'";
    mysqli_query($conn, $delete_candidate);
    
    header("Location: admin_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="text-center">Admin Dashboard</h2>

    <div class="d-flex justify-content-center gap-3 mt-4">
        <a href="reset_votes.php" class="btn btn-warning">Reset Votes</a>
        <a href="manage_candidates.php" class="btn btn-primary">Manage Candidates</a>
        <a href="manage_voters.php" class="btn btn-success">Manage Voters</a>
        <a href="admin_logout.php" class="btn btn-danger">Logout</a>
    </div>
</div>

</body>
</html>