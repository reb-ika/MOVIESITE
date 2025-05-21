<?php
require_once '../includes/db.php';
include '../includes/header.php';
// Fetch all movies
$stmt = $pdo->query("SELECT * FROM movies");
$movies = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Manage Movies</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Manage Movies</h2>
    <a href="add_movie.php" class="btn btn-primary mb-3">Add New Movie</a>

    <<table class="table table-striped table-hover align-middle text-center">

        <thead>
            <tr>
                <th>Poster</th>
                <th>Title</th>
                <th>Genre</th>
                <th>Year</th>
                <th>Rating</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($movies as $movie): ?>
            <tr>
                <td><img src="../assets/images/<?= htmlspecialchars($movie['poster']) ?>" width="70"></td>
                <td><?= htmlspecialchars($movie['title']) ?></td>
                <td><?= htmlspecialchars($movie['genre']) ?></td>
                <td><?= htmlspecialchars($movie['release_year']) ?></td>
                <td><?= htmlspecialchars($movie['rating']) ?></td>
                <td>
                    <a href="edit_movie.php?id=<?= $movie['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="delete_movie.php?id=<?= $movie['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
