<?php
require_once '../includes/db.php'; // Adjust if needed

header('Content-Type: application/json');

// Get optional search and genre parameters
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$genre = isset($_GET['genre']) ? trim($_GET['genre']) : '';

$sql = "SELECT * FROM movies WHERE 1";
$params = [];

// Add search filter
if (!empty($search)) {
    $sql .= " AND title LIKE :search";
    $params['search'] = "%" . $search . "%";
}

// Add genre filter
if (!empty($genre)) {
    $sql .= " AND genre = :genre";
    $params['genre'] = $genre;
}

// Order by newest first
$sql .= " ORDER BY id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$movies = $stmt->fetchAll();

// Return JSON response
echo json_encode($movies);
?>

