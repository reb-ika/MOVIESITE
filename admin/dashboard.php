
<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    // Redirect to login page or display a message
    header("Location: /moviesite/auth/login.html");
    exit;
}
require '../includes/db.php';
$query = $conn->prepare("SELECT * FROM movies ORDER BY id DESC");
$query->execute();
$movies = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="container py-5">
    <h2>🎬 Admin Dashboard</h2>
    <a href="add_movie.php" class="btn btn-success my-3">+ Add New Movie</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Title</th><th>Genre</th><th>Year</th><th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while($m = $movies->fetch_assoc()): ?>
                <tr>
                    <td><?= $m['title'] ?></td>
                    <td><?= $m['genre'] ?></td>
                    <td><?= $m['release_year'] ?></td>
                    <td>
                        <a href="edit_movie.php?id=<?= $m['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="delete_movie.php?id=<?= $m['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this movie?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile ?>
        </tbody>
    </table>
</body>
</html>
