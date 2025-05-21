<?php
require_once 'includes/db.php';
include 'includes/header.php';


// Get movie ID
$movieId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$movieId) {
    die("Movie not found.");
}

// Fetch movie details
$stmt = $pdo->prepare("SELECT * FROM movies WHERE id = ?");
$stmt->execute([$movieId]);
$movie = $stmt->fetch();

if (!$movie) {
    die("Movie not found.");
}

// Fetch reviews
$reviewStmt = $pdo->prepare("SELECT r.comment, r.rating, r.created_at, u.username FROM reviews r JOIN users u ON r.user_id = u.id WHERE r.movie_id = ?");
$reviewStmt->execute([$movieId]);
$reviews = $reviewStmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($movie['title']) ?> | Movie Details</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        iframe, video {
            width: 100%;
            max-height: 400px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body class="container py-5">

    <div class="row mb-4">
        <div class="col-md-4">
            <img src="assets/images/<?= $movie['poster'] ?>" class="img-fluid" alt="<?= htmlspecialchars($movie['title']) ?>">
        </div>
        <div class="col-md-8">
            <h2><?= htmlspecialchars($movie['title']) ?></h2>
            <p><strong>Genre:</strong> <?= htmlspecialchars($movie['genre']) ?></p>
            <p><strong>Release Date:</strong> <?= htmlspecialchars($movie['release_year']) ?></p>

            <p><?= nl2br(htmlspecialchars($movie['description'])) ?></p>

            <?php if (!empty($movie['trailer_url'])): ?>
                <iframe src="<?= $movie['trailer_url'] ?>" frameborder="0" allowfullscreen></iframe>
            

                
            <?php endif; ?>

            <?php if (isset($_SESSION['user_id'])): ?>
                <button class="btn btn-outline-danger me-2" onclick="likeMovie(<?= $movie['id'] ?>)">❤️ Like</button>
                <button class="btn btn-outline-warning me-2" onclick="addToWatchlist(<?= $movie['id'] ?>)">📺 Watchlist</button>
                <button class="btn btn-outline-info" onclick="addToFavorites(<?= $movie['id'] ?>)">⭐ Favorite</button>
            <?php endif; ?>
        </div>
    </div>

    <hr>

    <h4>📝 Reviews</h4>
    <div>
        <?php foreach ($reviews as $review): ?>
            <div class="border p-2 mb-2 rounded">
                <strong><?= htmlspecialchars($review['username']) ?></strong>
                <span>⭐ <?= $review['rating'] ?>/5</span>
                <p><?= htmlspecialchars($review['comment']) ?></p>
                <small class="text-muted"><?= $review['created_at'] ?></small>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if (isset($_SESSION['user_id'])): ?>
        <form action="api/review.php" method="POST" class="mt-4">
            <input type="hidden" name="movie_id" value="<?= $movie['id'] ?>">
            <div class="mb-2">
                <label>Rating (1 to 5)</label>
                <input type="number" name="rating" min="1" max="5" class="form-control" required>
            </div>
            <div class="mb-2">
                <label>Comment</label>
                <textarea name="comment" class="form-control" required></textarea>
            </div>
            <button class="btn btn-success">Submit Review</button>
        </form>
    <?php else: ?>
        <p><a href="auth/login.html">Login</a> to review this movie.</p>
    <?php endif; ?>

    <script>
        function likeMovie(id) {
            fetch('api/like.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'movie_id=' + id
            }).then(res => res.text()).then(alert);
        }

        function addToWatchlist(id) {
            fetch('api/watchlist.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'movie_id=' + id
            }).then(res => res.text()).then(alert);
        }

        function addToFavorites(id) {
            fetch('api/favorite.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'movie_id=' + id
            }).then(res => res.text()).then(alert);
        }
    </script>

</body>
</html>
