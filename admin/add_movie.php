<?php
require_once '../includes/db.php';

$message = '';

// Fetch genres for the dropdown
$stmt = $pdo->query("SELECT id, name FROM genres");
$genres = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $genre_id = $_POST['genre'];  // This is now the genre_id, not the name
    $release_year = $_POST['release_year'];
    $rating = $_POST['rating'];
    $description = $_POST['description'];
    $trailer_url = $_POST['trailer_url'];
    $poster = $_FILES['poster']['name'];

    $target = "../assets/images/" . basename($poster);

    if (move_uploaded_file($_FILES['poster']['tmp_name'], $target)) {
        // Insert the movie with the genre_id instead of the genre name
        $stmt = $pdo->prepare("INSERT INTO movies (title, genre_id, release_year, rating, description, trailer_url, poster) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $genre_id, $release_year, $rating, $description, $trailer_url, $poster]);
        $message = "Movie added successfully!";
    } else {
        $message = "Failed to upload poster image.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Add New Movie</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Add New Movie</h2>

    <?php if ($message): ?>
        <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Title</label>
            <input type="text" name="title" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Genre</label>
            <select name="genre" class="form-control" required>
                <?php foreach ($genres as $genre): ?>
                    <option value="<?= $genre['id'] ?>"><?= htmlspecialchars($genre['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Release Year</label>
            <input type="number" name="release_year" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Rating</label>
            <input type="number" name="rating" step="0.1" max="10" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control" required></textarea>
        </div>

        <div class="mb-3">
            <label>Trailer URL (optional)</label>
            <input type="url" name="trailer_url" class="form-control">
        </div>

        <div class="mb-3">
            <label>Poster Image</label>
            <input type="file" name="poster" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">Add Movie</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
