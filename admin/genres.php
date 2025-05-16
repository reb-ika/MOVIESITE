<?php
require_once '../includes/db.php';

// Fetch all genres
$stmt = $pdo->query("SELECT * FROM genres ORDER BY id DESC");
$genres = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Genres</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
    <div class="container">
        <h2 class="mb-4">All Genres</h2>
        <a href="add_genre.php" class="btn btn-success mb-3">Add New Genre</a>

        <?php if (count($genres) > 0): ?>
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Genre Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($genres as $genre): ?>
                        <tr>
                            <td><?= $genre['id'] ?></td>
                            <td><?= htmlspecialchars($genre['name']) ?></td>
                            <td>
                                <a href="edit_genre.php?id=<?= $genre['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="delete_genre.php?id=<?= $genre['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-info">No genres found.</div>
        <?php endif; ?>
    </div>
</body>
</html>
