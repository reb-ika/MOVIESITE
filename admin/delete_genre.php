<?php
require_once '../includes/db.php';

if (!isset($_GET['id'])) {
    die("Genre ID is missing.");
}

$id = $_GET['id'];

// Delete the genre
$stmt = $pdo->prepare("DELETE FROM genres WHERE id = ?");
$stmt->execute([$id]);

header("Location: genres.php");
exit;
?>
