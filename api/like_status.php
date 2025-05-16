<?php
require_once '../includes/db.php';
session_start();

header('Content-Type: application/json');

$userId = $_SESSION['user_id'] ?? null;
$movieId = $_GET['movie_id'] ?? null;

if (!$movieId) {
    echo json_encode(['status' => 'error', 'message' => 'Missing movie ID']);
    exit;
}

$liked = false;
if ($userId) {
    $stmt = $pdo->prepare("SELECT 1 FROM likes WHERE user_id = ? AND movie_id = ?");
    $stmt->execute([$userId, $movieId]);
    $liked = $stmt->fetchColumn() ? true : false;
}

$stmt = $pdo->prepare("SELECT COUNT(*) FROM likes WHERE movie_id = ?");
$stmt->execute([$movieId]);
$count = $stmt->fetchColumn();

echo json_encode([
    'status' => 'success',
    'liked' => $liked,
    'like_count' => $count
]);
?>
