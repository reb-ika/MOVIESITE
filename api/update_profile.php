<?php
require_once '../includes/db.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "Not logged in";
    exit;
}

$userId = $_SESSION['user_id'];
$name = $_POST['username'];
$password = $_POST['password'];
$profilePicture = $_FILES['profile_picture'];

$picturePath = null;

if ($profilePicture && $profilePicture['error'] === 0) {
    // Validate image file type
    $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
    if (in_array($profilePicture['type'], $allowedTypes)) {
        // Rename file to avoid conflicts
        $ext = pathinfo($profilePicture['name'], PATHINFO_EXTENSION);
        $newFileName = uniqid("profile_", true) . '.' . $ext;
        $uploadPath = '../uploads/profile_pictures/' . $newFileName;

        if (move_uploaded_file($profilePicture['tmp_name'], $uploadPath)) {
            $picturePath = $newFileName;
        }
    }
}
if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === 0) {
    $uploadDir = '../uploads/profile_pictures/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true); // Create folder if not exists
    }

    $filename = basename($_FILES['profile_picture']['username']);
    $targetFile = $uploadDir . time() . '_' . $filename; // prevent duplicate names

    if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $targetFile)) {
        $profile_picture = basename($targetFile); // just save filename to DB
    }
}

// Update database
$sql = "UPDATE users SET username = :username, password = :password";
$params = [
    ':username' => $name,
    ':password' => $password,
    ':id' => $userId,
];

if ($picturePath !== null) {
    $sql .= ", profile_picture = :profile_picture";
    $params[':profile_picture'] = $picturePath;
}

$sql .= " WHERE id = :id";

$stmt = $pdo->prepare($sql);
if ($stmt->execute($params)) {
    echo "Profile updated successfully!";
} else {
    echo "Update failed!";
}
?>
