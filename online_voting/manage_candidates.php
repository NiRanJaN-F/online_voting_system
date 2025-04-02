<?php
session_start();
include 'db.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Handle candidate addition
if (isset($_POST['add_candidate'])) {
    $id = (int) $_POST['id']; // Convert to integer
    $name = mysqli_real_escape_string($conn, $_POST['name']);

    // Check if ID already exists
    $check_query = "SELECT * FROM candidates WHERE id = $id";
    $check_result = mysqli_query($conn, $check_query);
    
    if (mysqli_num_rows($check_result) > 0) {
        $error = "Candidate ID already exists!";
    } else {
        $query = "INSERT INTO candidates (id, name, votes) VALUES ($id, '$name', 0)";
        if (mysqli_query($conn, $query)) {
            $success = "Candidate added successfully!";
        } else {
            $error = "Error adding candidate!";
        }
    }
}

// Handle candidate deletion
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $query = "DELETE FROM candidates WHERE id = $delete_id";
    mysqli_query($conn, $query);
    header("Location: manage_candidates.php");
    exit();
}

// Fetch all candidates
$candidates = mysqli_query($conn, "SELECT * FROM candidates");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Candidates</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="text-center">Manage Candidates</h2>

    <div class="card p-3 shadow mt-4">
        <h4>Add New Candidate</h4>
        <?php if (isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>
        <?php if (isset($success)) { echo "<div class='alert alert-success'>$success</div>"; } ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Candidate ID</label>
                <input type="number" name="id" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Candidate Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <button type="submit" name="add_candidate" class="btn btn-success">Add Candidate</button>
        </form>
    </div>

    <div class="card p-3 shadow mt-4">
        <h4>Candidate List</h4>
        <table class="table table-bordered mt-3">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Votes</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($candidates)) { ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= $row['name'] ?></td>
                        <td><?= $row['votes'] ?></td>
                        <td>
                            <a href="edit_candidate.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="manage_candidates.php?delete_id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>
