<?php
require_once '../includes/db.php';
include '../includes/header.php';
$message = '';
$movie_id = $_GET['id']; // Get movie ID from the URL

// Fetch movie details from the database
$stmt = $pdo->prepare("SELECT * FROM movies WHERE id = ?");
$stmt->execute([$movie_id]);
$movie = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $movie_id = $_GET['id'];
    $title = $_POST['title'];
    $genre = $_POST['genre'];
    $release_year = $_POST['release_year'];
    $rating = $_POST['rating'];
    $description = $_POST['description'];
    $trailer_url = $_POST['trailer_url'];

    // Handle poster upload if a new image is uploaded
    if ($_FILES['poster']['name']) {
        $poster = $_FILES['poster']['name'];
        $target = "../assets/images/" . basename($poster);
        if (move_uploaded_file($_FILES['poster']['tmp_name'], $target)) {
            $stmt = $pdo->prepare("UPDATE movies SET title = ?, genre = ?, release_year = ?, rating = ?, description = ?, trailer_url = ?, poster = ? WHERE id = ?");
            $stmt->execute([$title, $genre, $release_year, $rating, $description, $trailer_url, $poster, $movie_id]);
            $message = "Movie updated successfully!";
        } else {
            $message = "Failed to upload new poster image.";
        }
    } else {
        // If no new poster is uploaded, update without the poster
        $stmt = $pdo->prepare("UPDATE movies SET title = ?, genre = ?, release_year = ?, rating = ?, description = ?, trailer_url = ? WHERE id = ?");
        $stmt->execute([$title, $genre, $release_year, $rating, $description, $trailer_url, $movie_id]);
        $message = "Movie updated successfully!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Edit Movie</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Edit Movie</h2>

    <?php if ($message): ?>
        <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Title</label>
            <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($movie['title']) ?>" required>
        </div>

        <div class="mb-3">
            <label>Genre</label>
            <input type="text" name="genre" class="form-control" value="<?= htmlspecialchars($movie['genre']) ?>" required>
        </div>

        <div class="mb-3">
            <label>Release Year</label>
            <input type="number" name="release_year" class="form-control" value="<?= htmlspecialchars($movie['release_year']) ?>" required>
        </div>

        <div class="mb-3">
            <label>Rating</label>
            <input type="number" name="rating" step="0.1" max="10" class="form-control" value="<?= htmlspecialchars($movie['rating']) ?>" required>
        </div>

        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control" required><?= htmlspecialchars($movie['description']) ?></textarea>
        </div>

        <div class="mb-3">
            <label>Trailer URL (optional)</label>
            <input type="url" name="trailer_url" class="form-control" value="<?= htmlspecialchars($movie['trailer_url']) ?>">
        </div>

        <div class="mb-3">
            <label>Poster Image (optional, leave blank to keep existing)</label>
            <input type="file" name="poster" class="form-control">
            <br>
            <img src="../assets/images/<?= htmlspecialchars($movie['poster']) ?>" alt="Poster" width="100">
        </div>

        <button type="submit" class="btn btn-success">Update Movie</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

