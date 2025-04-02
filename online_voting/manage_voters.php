<?php
session_start();
include 'db.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Handle adding voters
if (isset($_POST['add_voter'])) {
    $voter_id = mysqli_real_escape_string($conn, $_POST['voter_id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $age = (int) $_POST['age'];

    $query = "INSERT INTO voter_list (voter_id, name, age) VALUES ('$voter_id', '$name', $age)";
    if (mysqli_query($conn, $query)) {
        $success = "Voter added successfully!";
    } else {
        $error = "Error adding voter!";
    }
}

// Handle deleting voters
if (isset($_GET['delete'])) {
    $voter_id = mysqli_real_escape_string($conn, $_GET['delete']);
    mysqli_query($conn, "DELETE FROM voter_list WHERE voter_id='$voter_id'");
    header("Location: manage_voters.php");
    exit();
}

// Handle editing voters
if (isset($_POST['edit_voter'])) {
    $voter_id = mysqli_real_escape_string($conn, $_POST['voter_id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $age = (int) $_POST['age'];

    $query = "UPDATE voter_list SET name='$name', age=$age WHERE voter_id='$voter_id'";
    if (mysqli_query($conn, $query)) {
        $success = "Voter updated successfully!";
    } else {
        $error = "Error updating voter!";
    }
}

// Fetch all voters
$voters = mysqli_query($conn, "SELECT * FROM voter_list");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Voters</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="text-center">Manage Voters</h2>

    <div class="card p-3 shadow mt-4">
        <h4>Add New Voter</h4>
        <?php if (isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>
        <?php if (isset($success)) { echo "<div class='alert alert-success'>$success</div>"; } ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Voter ID</label>
                <input type="text" name="voter_id" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Age</label>
                <input type="number" name="age" class="form-control" required>
            </div>
            <button type="submit" name="add_voter" class="btn btn-success">Add Voter</button>
        </form>
    </div>

    <div class="card p-3 shadow mt-4">
        <h4>Voter List</h4>
        <table class="table table-bordered mt-3">
            <thead class="table-dark">
                <tr>
                    <th>Voter ID</th>
                    <th>Name</th>
                    <th>Age</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($voters)) { ?>
                    <tr>
                        <td><?= $row['voter_id'] ?></td>
                        <td><?= $row['name'] ?></td>
                        <td><?= $row['age'] ?></td>
                        <td>
                            <a href="?delete=<?= $row['voter_id'] ?>" class="btn btn-danger btn-sm">Delete</a>
                            <button class="btn btn-primary btn-sm" onclick="editVoter('<?= $row['voter_id'] ?>', '<?= $row['name'] ?>', <?= $row['age'] ?>)">Edit</button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Voter</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST">
                    <input type="hidden" name="voter_id" id="edit_voter_id">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Age</label>
                        <input type="number" name="age" id="edit_age" class="form-control" required>
                    </div>
                    <button type="submit" name="edit_voter" class="btn btn-primary">Update Voter</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function editVoter(voter_id, name, age) {
    document.getElementById('edit_voter_id').value = voter_id;
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_age').value = age;
    new bootstrap.Modal(document.getElementById('editModal')).show();
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
