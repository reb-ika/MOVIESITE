<?php
require_once '../includes/db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Optional: delete the poster image from the server (if needed)
    $stmt = $pdo->prepare("SELECT poster FROM movies WHERE id = ?");
    $stmt->execute([$id]);
    $movie = $stmt->fetch();

    if ($movie && file_exists("../assets/images/" . $movie['poster'])) {
        unlink("../assets/images/" . $movie['poster']);
    }

    // Delete the movie from the database
    $stmt = $pdo->prepare("DELETE FROM movies WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: movies.php?msg=Movie deleted");
    exit;
}
?>
