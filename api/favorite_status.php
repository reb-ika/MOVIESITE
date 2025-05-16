<?php
session_start();
header('Content-Type: application/json');
require_once '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];
$movie_id = $_GET['movie_id'] ?? null;

if (!$movie_id) {
    echo json_encode(['status' => 'error', 'message' => 'Missing movie ID']);
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM favorites WHERE user_id = :user_id AND movie_id = :movie_id");
$stmt->execute(['user_id' => $user_id, 'movie_id' => $movie_id]);
$favorited = $stmt->fetch() ? true : false;

echo json_encode(['status' => 'success', 'favorited' => $favorited]);
