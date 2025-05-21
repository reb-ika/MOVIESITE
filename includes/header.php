<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?= isset($title) ? htmlspecialchars($title) : 'Movie Site'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&family=Helvetica+Neue:wght@400;500;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <style>
        /* Put your CSS styles here or in a separate CSS file */
        body {
            background: linear-gradient(135deg, #f8e6f0, #fff0f5);
            color: #333;
            font-family: 'Helvetica Neue', Arial, sans-serif;
        }
        .navbar {
            background-color: #ff69b4 !important;
            padding: 10px 0;
            position: fixed;
            top: 0; width: 100%;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(255, 20, 147, 0.3);
        }
        .nav-link {
            color: #fff !important;
            font-family: 'Dancing Script', cursive;
            margin-left: 15px;
        }
        .nav-link:hover {
            color: #ff1493 !important;
        }
        .container {
            margin-top: 70px;
            max-width: 1200px;
            padding: 0 20px;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="/MOVIE/index.php">
            <img src="/MOVIE/images/logo.png" alt="Movie Site" style="height: 40px; border-radius: 50%;" />
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" >
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="/MOVIE/movies.php">Movies</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item"><a class="nav-link" href="/MOVIE/profile.php">Profile</a></li>
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                        <li class="nav-item"><a class="nav-link" href="/MOVIE/admin_dashboard.php">Admin</a></li>
                    <?php endif; ?>
                    <li class="nav-item"><a class="nav-link" href="/MOVIE/logout.php">Logout</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="/MOVIE/login.php">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="/MOVIE/register.php">Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<div class="container">
