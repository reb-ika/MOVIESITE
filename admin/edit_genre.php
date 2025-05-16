<?php
require_once '../includes/db.php';

if (!isset($_GET['id'])) {
    die("Genre ID not provided.");
}

$id = $_GET['id'];

// Fetch genre data
$stmt = $pdo->prepare("SELECT * FROM genres WHERE id = ?");
$stmt->execute([$id]);
$genre = $stmt->fetch();

if (!$genre) {
    die("Genre not found.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);

    if (!empty($name)) {
        $update = $pdo->prepare("UPDATE genres SET name = ? WHERE id = ?");
        $update->execute([$name, $id]);
        header("Location: genres.php");
        exit;
    } else {
        $error = "Genre name cannot be empty.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Genre</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container">
    <h2>Edit Genre</h2>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label for="name" class="form-label">Genre Name</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($genre['name']) ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Genre</button>
        <a href="genres.php" class="btn btn-secondary">Back</a>
    </form>
</div>
</body>
</html>
