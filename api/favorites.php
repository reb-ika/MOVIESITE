<?php
session_start();
header('Content-Type: application/json');
require_once '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'You must be logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];
$movie_id = $_GET['movie_id'] ?? null;

if (!$movie_id) {
    echo json_encode(['status' => 'error', 'message' => 'Movie ID missing']);
    exit;
}

// Check if already in favorites
$stmt = $pdo->prepare("SELECT * FROM favorites WHERE user_id = :user_id AND movie_id = :movie_id");
$stmt->execute(['user_id' => $user_id, 'movie_id' => $movie_id]);
$favorite = $stmt->fetch();

if ($favorite) {
    // Remove from favorites
    $pdo->prepare("DELETE FROM favorites WHERE user_id = :user_id AND movie_id = :movie_id")
        ->execute(['user_id' => $user_id, 'movie_id' => $movie_id]);
    echo json_encode(['status' => 'success', 'action' => 'removed']);
} else {
    // Add to favorites
    $pdo->prepare("INSERT INTO favorites (user_id, movie_id) VALUES (:user_id, :movie_id)")
        ->execute(['user_id' => $user_id, 'movie_id' => $movie_id]);
    echo json_encode(['status' => 'success', 'action' => 'added']);
}
