<?php
require_once '../includes/db.php';
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
    exit;
}

if (!isset($_SESSION['user_id']) || !isset($_GET['movie_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Missing data']);
    exit;
}

$user_id = $_SESSION['user_id'];
$movie_id = (int) $_GET['movie_id'];

// Check if already in watchlist
$stmt = $pdo->prepare("SELECT * FROM watchlist WHERE user_id = ? AND movie_id = ?");
$stmt->execute([$user_id, $movie_id]);
$existing = $stmt->fetch();

if ($existing) {
    // Remove from watchlist
    $stmt = $pdo->prepare("DELETE FROM watchlist WHERE user_id = ? AND movie_id = ?");
    $stmt->execute([$user_id, $movie_id]);
    echo json_encode(['status' => 'success', 'action' => 'removed']);
} else {
    // Add to watchlist
    $stmt = $pdo->prepare("INSERT INTO watchlist (user_id, movie_id) VALUES (?, ?)");
    $stmt->execute([$user_id, $movie_id]);
    echo json_encode(['status' => 'success', 'action' => 'added']);
}
?>
