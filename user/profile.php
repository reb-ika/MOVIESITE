<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.html');
    exit;
}

$userId = $_SESSION['user_id'];

// Fetch user info
$stmt = $pdo->prepare("SELECT username, email, created_at FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

// Fetch user liked movies
$likedStmt = $pdo->prepare("SELECT m.* FROM likes l JOIN movies m ON l.movie_id = m.id WHERE l.user_id = ?");
$likedStmt->execute([$userId]);
$likedMovies = $likedStmt->fetchAll();

// Fetch user favorites
$favStmt = $pdo->prepare("SELECT m.* FROM favorites f JOIN movies m ON f.movie_id = m.id WHERE f.user_id = ?");
$favStmt->execute([$userId]);
$favorites = $favStmt->fetchAll();

// Fetch watchlist
$watchStmt = $pdo->prepare("SELECT m.* FROM watchlist w JOIN movies m ON w.movie_id = m.id WHERE w.user_id = ?");
$watchStmt->execute([$userId]);
$watchlist = $watchStmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Profile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        .movie-card img {
            height: 150px;
            object-fit: cover;
        }
    </style>
</head>
<body class="container py-5">
    <h2>👤 Welcome, <?= htmlspecialchars($user['username']) ?></h2>
    <p>Email: <?= htmlspecialchars($user['email']) ?></p>
    <p>Member Since: <?= htmlspecialchars(date('F j, Y', strtotime($user['created_at']))) ?></p>
    
    <hr>
    <h3>Edit Info</h3>
    <form action="/moviesite/api/update_profile.php" method="POST" enctype="multipart/form-data">

        <div class="mb-2">
            <label>Name</label>
            <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" class="form-control" required>
        </div>
        <div class="mb-2">
            <label>New Password</label>
            <input type="password" name="password" class="form-control" placeholder="Leave blank to keep current">
        </div>
        <div class="mb-3">
        <label for="profile_picture" class="form-label">Upload Profile Picture</label>
        <input type="file" name="profile_picture" id="profile_picture" class="form-control">
    </div>
        <button class="btn btn-primary">Update</button>
    </form>
    <?php if (!empty($user['profile_picture'])): ?>
    <img src="/moviesite/uploads/profile_pictures/<?= htmlspecialchars($user['profile_picture']) ?>" alt="Profile Picture" width="100" height="100">
    <?php endif; ?>

    <hr>
    <h3>❤️ Liked Movies</h3>
    <div class="row">
        <?php foreach ($likedMovies as $movie): ?>
            <div class="col-md-3 mb-3 movie-card">
                <div class="card">
                    <img src="../assets/images/<?= $movie['poster'] ?>" class="card-img-top">
                    <div class="card-body">
                        <h5><?= htmlspecialchars($movie['title']) ?></h5>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <h3>⭐ Favorites</h3>
    <div class="row">
        <?php foreach ($favorites as $movie): ?>
            <div class="col-md-3 mb-3 movie-card">
                <div class="card">
                    <img src="../assets/images/<?= $movie['poster'] ?>" class="card-img-top">
                    <div class="card-body">
                        <h5><?= htmlspecialchars($movie['title']) ?></h5>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <h3>📺 Watchlist</h3>
    <div class="row">
        <?php foreach ($watchlist as $movie): ?>
            <div class="col-md-3 mb-3 movie-card">
                <div class="card">
                    <img src="../assets/images/<?= $movie['poster'] ?>" class="card-img-top">
                    <div class="card-body">
                        <h5><?= htmlspecialchars($movie['title']) ?></h5>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</body>
</html>
