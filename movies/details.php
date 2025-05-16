<?php
require_once '../includes/db.php';

if (!isset($_GET['id'])) {
    die('Movie ID not specified.');
}

$id = (int) $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM movies WHERE id = ?");
$stmt->execute([$id]);
$movie = $stmt->fetch();

if (!$movie) {
    die('Movie not found.');
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($movie['title']) ?> - Details</title>
   
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>

<div class="container mt-4">
    <a href="../index.php" class="btn btn-secondary mb-3">← Back to Listings</a>
    
    <div class="row">
        <div class="col-md-4">
            <img src="../assets/images/<?= htmlspecialchars($movie['poster']) ?>" alt="Poster" class="img-fluid rounded shadow">
        </div>
        <div class="col-md-8">
            <h2><?= htmlspecialchars($movie['title']) ?></h2>
            <p><strong>Genre:</strong> <?= htmlspecialchars($movie['genre']) ?></p>
            <p><strong>Release Year:</strong> <?= htmlspecialchars($movie['release_year']) ?></p>
            <p><strong>Rating:</strong> ⭐ <?= htmlspecialchars($movie['rating']) ?>/10</p>
            <p><strong>Description:</strong></p>
            <p><?= nl2br(htmlspecialchars($movie['description'])) ?></p>

            <?php if (!empty($movie['trailer_url'])): ?>
                <div class="ratio ratio-16x9 mt-3">
                    <iframe src="<?= htmlspecialchars($movie['trailer_url']) ?>" frameborder="0" allowfullscreen></iframe>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
