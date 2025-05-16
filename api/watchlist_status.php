<?php
require_once '../includes/db.php';
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || !isset($_GET['movie_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Missing data']);
    exit;
}

$user_id = $_SESSION['user_id'];
$movie_id = (int) $_GET['movie_id'];

$stmt = $pdo->prepare("SELECT * FROM watchlist WHERE user_id = ? AND movie_id = ?");
$stmt->execute([$user_id, $movie_id]);
$exists = $stmt->fetch();

echo json_encode([
    'status' => 'success',
    'in_watchlist' => $exists ? true : false
]);
?>
