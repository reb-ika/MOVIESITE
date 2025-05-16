<?php
require_once 'includes/db.php';

// Fetch all movies
$stmt = $pdo->query("SELECT * FROM movies ORDER BY created_at DESC");
$movies = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Browse All Movies</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        .movie-card img {
            height: 300px;
            object-fit: cover;
        }
        .movie-title {
            font-size: 1.1rem;
            font-weight: bold;
        }
    </style>
</head>
<body class="container py-5">
    <h2 class="mb-4">🎬 Browse All Movies</h2>

    <div class="row">
        <?php foreach ($movies as $movie): ?>
            <div class="col-md-3 col-sm-6 mb-4 movie-card">
                <div class="card h-100">
                    <a href="movie.php?id=<?= $movie['id'] ?>">
                        <img src="assets/images/<?= $movie['poster'] ?>" class="card-img-top" alt="<?= htmlspecialchars($movie['title']) ?>">
                    </a>
                    <div class="card-body">
                        <p class="movie-title"><?= htmlspecialchars($movie['title']) ?></p>
                        <p class="text-muted"><?= htmlspecialchars($movie['genre']) ?> | <?= date('Y', strtotime($movie['release_year'])) ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
