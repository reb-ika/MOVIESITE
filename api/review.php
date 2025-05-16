<?php
session_start();
require_once '../includes/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo "You must be logged in to submit a review.";
    exit;
}

$userId = $_SESSION['user_id'];
$movieId = isset($_POST['movie_id']) ? intval($_POST['movie_id']) : 0;
$rating = isset($_POST['rating']) ? intval($_POST['rating']) : 0;
$comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';

// Validate inputs
if ($movieId <= 0 || $rating < 1 || $rating > 5 || empty($comment)) {
    http_response_code(400);
    echo "Invalid input.";
    exit;
}

try {
    // Insert or update review (one review per user per movie)
    $stmt = $pdo->prepare("
        INSERT INTO reviews (user_id, movie_id, rating, comment, created_at)
        VALUES (:user_id, :movie_id, :rating, :comment, NOW())
        ON DUPLICATE KEY UPDATE rating = :rating, comment = :comment, created_at = NOW()
    ");

    $stmt->execute([
        ':user_id' => $userId,
        ':movie_id' => $movieId,
        ':rating' => $rating,
        ':comment' => $comment
    ]);

    header("Location: ../movie.php?id=" . $movieId);
    exit;

} catch (PDOException $e) {
    http_response_code(500);
    echo "Error saving review: " . $e->getMessage();
    exit;
}
