<?php
require_once '../includes/db.php'; // adjust path if needed

include '../includes/header.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];

    $stmt = $pdo->prepare("INSERT INTO genres (name) VALUES (?)");
    $stmt->execute([$name]);

    header("Location: add_genre.php?success=1");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Add Genre</title>
    <!-- Bootstrap via CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
    <div class="container">
        <h2 class="mb-4">Add Genre</h2>
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">Genre added successfully.</div>
        <?php endif; ?>
        <form method="post">
            <div class="mb-3">
                <label class="form-label">Genre Name:</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Genre</button>
        </form>
    </div>
</body>
</html>

