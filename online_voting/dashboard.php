<?php
session_start();
include 'db.php'; // Database connection

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="text-center mb-4">Welcome to the Voting System</h2>

    <!-- Navigation Links -->
    <div class="text-center mb-4">
        <a href="vote.php" class="btn btn-primary">Vote Now</a>
        <a href="results.php" class="btn btn-success">View Results</a>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>

    <!-- Live Voting Results Table -->
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Live Voting Results</h5>
        </div>
        <div class="card-body">
            <table class="table table-bordered text-center">
                <thead class="table-dark">
                    <tr>
                        <th>Candidate</th>
                        <th>Votes</th>
                    </tr>
                </thead>
                <tbody id="voteResults">
                    <!-- Live vote data will be loaded here -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
// Function to fetch live vote results
function fetchVotes() {
    $.ajax({
        url: "fetch_votes.php", 
        method: "GET",
        dataType: "json",
        success: function(data) {
            let tableRows = "";
            data.forEach(function(candidate) {
                tableRows += `<tr><td>${candidate.name}</td><td>${candidate.votes}</td></tr>`;
            });
            $("#voteResults").html(tableRows);
        }
    });
}

// Auto-update votes every 3 seconds
setInterval(fetchVotes, 3000);
fetchVotes();
</script>

</body>
</html>
