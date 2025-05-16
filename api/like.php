<?php
require_once '../includes/db.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$movieId = $data['movie_id'] ?? null;
$userId = $_SESSION['user_id'] ?? null;

if (!$userId || !$movieId) {
    echo json_encode(['error' => 'Missing user or movie ID']);
    exit;
}

// Check if already liked
$stmt = $pdo->prepare("SELECT * FROM likes WHERE user_id = ? AND movie_id = ?");
$stmt->execute([$userId, $movieId]);
$liked = $stmt->fetch();

if ($liked) {
  // Unlike it
  $stmt = $pdo->prepare("DELETE FROM likes WHERE user_id = ? AND movie_id = ?");
  $stmt->execute([$userId, $movieId]);
  echo json_encode(['liked' => false]);
} else {
  // Like it
  $stmt = $pdo->prepare("INSERT INTO likes (user_id, movie_id) VALUES (?, ?)");
  $stmt->execute([$userId, $movieId]);
  echo json_encode(['liked' => true]);
}
?>
