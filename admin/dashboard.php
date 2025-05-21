<?php
session_start();
// Uncomment this if you want to protect the page
// if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
//     header("Location: /moviesite/auth/login.html");
//     exit;
// }

require '../includes/db.php';
include '../includes/header.php';
// Fetch movies
$stmt = $pdo->prepare("SELECT * FROM movies ORDER BY id DESC");
$stmt->execute();
$movies = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch genres
$stmt = $pdo->prepare("SELECT * FROM genres ORDER BY id DESC");
$stmt->execute();
$genres = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch users
$stmt = $pdo->prepare("SELECT * FROM users ORDER BY id DESC");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="container py-5">

    <h2>🎬 Admin Dashboard</h2>
    
    <!-- Movies Section -->
    <h4 class="mt-4">📽 Movies</h4>
    <a href="add_movie.php" class="btn btn-success mb-2">+ Add New Movie</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Title</th><th>Genre</th><th>Year</th><th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($movies as $m): ?>
                <tr>
                    <td><?= htmlspecialchars($m['title']) ?></td>
                    <td><?= htmlspecialchars($m['genre']) ?></td>
                    <td><?= htmlspecialchars($m['release_year']) ?></td>
                    <td>
                        <a href="edit_movie.php?id=<?= $m['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="delete_movie.php?id=<?= $m['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this movie?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>

    <!-- Genres Section -->
    <h4 class="mt-5">🎞 Genres</h4>
    <a href="add_genre.php" class="btn btn-primary mb-2">+ Add New Genre</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th><th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($genres as $g): ?>
                <tr>
                    <td><?= htmlspecialchars($g['name']) ?></td>
                    
                    <td>
                        <a href="edit_genre.php?id=<?= $g['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="delete_genre.php?id=<?= $g['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this genre?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>

    <!-- Users Section -->
    <h4 class="mt-5">👤 Users</h4>
    <a href="add_user.php" class="btn btn-info mb-2">+ Add New User</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Username</th><th>Email</th><th>Role</th><th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($users as $u): ?>
                <tr>
                    <td><?= htmlspecialchars($u['username']) ?></td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td><?= htmlspecialchars($u['role']) ?></td>
                    <td>
                        <a href="edit_user.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="delete_user.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this user?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>

</body>
</html>
