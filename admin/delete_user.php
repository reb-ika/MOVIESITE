<?php
require_once '../includes/db.php';

if (!isset($_GET['id'])) {
    die("User ID is required.");
}

$user_id = $_GET['id'];

// Delete user from the database
$stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
$stmt->execute([$user_id]);

header("Location: users.php"); // Redirect back to user management page
exit;
?>
