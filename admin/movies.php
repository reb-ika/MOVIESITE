<?php
require_once '../includes/db.php';
include '../includes/header.php';
// Fetch all movies with the genre name
$stmt = $pdo->query("SELECT movies.id, movies.title, movies.description, movies.release_year, genres.name AS genre 
                     FROM movies
                     JOIN genres ON movies.genre_id = genres.id
                     ORDER BY movies.id DESC");
$movies = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Movies</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
    <div class="container">
        <h2 class="mb-4">Movies List</h2>
        <a href="add_movie.php" class="btn btn-success mb-3">Add New Movie</a>

        <?php if (count($movies) > 0): ?>
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Genre</th>
                        <th>Release Year</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($movies as $movie): ?>
                        <tr>
                            <td><?= $movie['id'] ?></td>
                            <td><?= htmlspecialchars($movie['title']) ?></td>
                            <td><?= htmlspecialchars($movie['description']) ?></td>
                            <td><?= htmlspecialchars($movie['genre']) ?></td>
                            <td><?= $movie['release_year'] ?></td>
                            <td>
                                <a href="edit_movie.php?id=<?= $movie['id'] ?>" class="btn btn-warning">Edit</a>
                                <a href="delete_movie.php?id=<?= $movie['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this movie?');">Delete</a>

                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-info">No movies found.</div>
        <?php endif; ?>
    </div>
</body>
</html>
