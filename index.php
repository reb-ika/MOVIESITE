    <?php
    require_once 'includes/db.php';
    include 'includes/header.php';
    

    // Fetch movies with optional search and genre filter
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    $genre = isset($_GET['genre']) ? trim($_GET['genre']) : '';

    $sql = "SELECT * FROM movies WHERE 1";
    $params = [];

    if ($search !== '') {
        $sql .= " AND title LIKE :search";
        $params['search'] = "%$search%";
    }

    if ($genre !== '') {
        $sql .= " AND genre = :genre";
        $params['genre'] = $genre;
    }

    $sql .= " ORDER BY id DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $movies = $stmt->fetchAll();

    // Check if user is logged in (to enable like functionality)
    $isLoggedIn = isset($_SESSION['user_id']);
    $user = null;
if ($isLoggedIn) {
    $stmtUser = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmtUser->execute([$_SESSION['user_id']]);
    $user = $stmtUser->fetch();
}
?>
    


    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>Moviesite - Home</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
        <style>
            .movie-card img {
                height: 300px;
                object-fit: cover;
            }

            .movie-card {
                margin-bottom: 30px;
            }

            .like-btn {
                margin-top: 10px;
            }
        </style>
        <?php if ($isLoggedIn && $user): ?>
            <nav class="navbar navbar-light bg-light justify-content-between px-4">
                <span class="navbar-text">
                    Welcome, <?= htmlspecialchars($user['username']) ?>
                    </span>
                    <div class="d-flex align-items-center">
                        <?php if (!empty($user['profile_picture'])): ?>
                             <img src="../uploads/profile_pictures/<?= htmlspecialchars($user['profile_picture']) ?>" alt="Profile" style="width: 40px; height: 40px; object-fit: cover; border-radius: 50%; margin-right: 10px;">
                        <?php else: ?>
                            <a href="logout.php" class="btn btn-outline-danger btn-sm">Logout</a>
                            <?php endif; ?>
                            </div>
            </nav>
        <?php endif; ?>

    </head>

    <body>

        <!-- Search & Filter Form -->
        <form action="index.php" method="GET" class="mb-4 d-flex" style="gap: 10px; flex-wrap: wrap;">
            <input type="text" name="search" class="form-control" placeholder="Search movies..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">

            <select name="genre" class="form-select" style="max-width: 200px;">
                <option value="">All Genres</option>
                <option value="Action" <?= (($_GET['genre'] ?? '') == 'Action') ? 'selected' : '' ?>>Action</option>
                <option value="Drama" <?= (($_GET['genre'] ?? '') == 'Drama') ? 'selected' : '' ?>>Drama</option>
                <option value="Comedy" <?= (($_GET['genre'] ?? '') == 'Comedy') ? 'selected' : '' ?>>Comedy</option>
                <option value="Sci-Fi" <?= (($_GET['genre'] ?? '') == 'Sci-Fi') ? 'selected' : '' ?>>Sci-Fi</option>
            </select>

            <button type="submit" class="btn btn-primary">Filter</button>
        </form>
        <img src="uploads/<?php echo $user['profile_picture']; ?>" alt="Profile Picture" style="width:100px; height:100px;">


        <!-- Movie List -->
        <div class="container mt-5">
            <h1 class="mb-4 text-center">🎬 Movie Listings</h1>
            <div class="row" id="movie-list">
                <?php foreach ($movies as $movie): ?>
                    <div class="col-md-4 movie-card">
                        <div class="card h-100">
                            <a href="movie.php?id=<?= $movie['id'] ?>">
                                <img src="assets/images/<?= $movie['poster'] ?>" class="card-img-top" alt="<?= htmlspecialchars($movie['title']) ?>">
                            </a>
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($movie['title']) ?></h5>
                                <p class="card-text"><?= htmlspecialchars($movie['description']) ?></p>
                                <span class="badge bg-info"><?= htmlspecialchars($movie['genre']) ?></span>
                                <!-- Like Button -->
                                <?php if ($isLoggedIn): ?>
                                    <button class="like-btn" id="like-btn-<?= $movie['id'] ?>" onclick="toggleLike(<?= $movie['id'] ?>)">
                                        🤍
                                    </button>
                                    <button class="btn btn-sm btn-outline-warning mt-2" onclick="toggleFavorite(<?= $movie['id'] ?>)" id="fav-btn-<?= $movie['id'] ?>">⭐</button>
                                    <button class="watchlist-btn" id="watchlist-btn-<?= $movie['id'] ?>" onclick="toggleWatchlist(<?= $movie['id'] ?>)">
                                         ➕
                                    </button>
                                    <span id="like-count-<?= $movie['id'] ?>" class="ms-2">0</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <script>
            async function toggleLike(movieId) {
                const likeBtn = document.getElementById(`like-btn-${movieId}`);
                const countSpan = document.getElementById(`like-count-${movieId}`);

                try {
                    const response = await fetch('api/like.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            movie_id: movieId
                        })
                    });
                    const data = await response.json();

                    if (data.status === 'success') {
                        likeBtn.textContent = data.action === 'liked' ? '💖' : '🤍';
                        countSpan.textContent = data.like_count;
                    } else {
                        alert(data.message);
                    }
                } catch (error) {
                    console.error('Like error:', error);
                }
            }

            async function loadLikeStatuses() {
                const movieIds = <?= json_encode(array_column($movies, 'id')) ?>;

                for (const movieId of movieIds) {
                    const likeBtn = document.getElementById(`like-btn-${movieId}`);
                    const countSpan = document.getElementById(`like-count-${movieId}`);

                    try {
                        const response = await fetch(`api/like_status.php?movie_id=${movieId}`);
                        const data = await response.json();

                        if (data.status === 'success') {
                            likeBtn.textContent = data.liked ? '💖' : '🤍';
                            countSpan.textContent = data.like_count;
                        }
                    } catch (error) {
                        console.error('Status error:', error);
                    }
                }
            }
            document.addEventListener('DOMContentLoaded', () => {
                loadLikeStatuses();
                // Load favorite statuses
                loadFavoriteStatuses(); 
            });
            // Load favorite statuses on page load
            document.addEventListener('DOMContentLoaded', loadLikeStatuses);
            async function toggleFavorite(movieId) {
    const favBtn = document.getElementById(`fav-btn-${movieId}`);

    try {
        const response = await fetch(`api/favorites.php?movie_id=${movieId}`);
        const data = await response.json();

        if (data.status === 'success') {
            favBtn.textContent = data.action === 'added' ? '🌟' : '⭐';
        } else {
            alert(data.message);
        }
    } catch (error) {
        console.error('Favorite error:', error);
    }
}

async function loadFavoriteStatuses() {
    const movieIds = <?= json_encode(array_column($movies, 'id')) ?>;

    for (const movieId of movieIds) {
        const favBtn = document.getElementById(`fav-btn-${movieId}`);
        try {
            const response = await fetch(`api/favorite_status.php?movie_id=${movieId}`);
            const data = await response.json();
            if (data.status === 'success') {
                favBtn.textContent = data.favorited ? '🌟' : '⭐';
            }
        } catch (error) {
            console.error('Favorite status error:', error);
        }
    }
}

async function toggleWatchlist(movieId) {
    const watchlistBtn = document.getElementById(`watchlist-btn-${movieId}`);

    try {
        const response = await fetch(`api/watchlist.php?movie_id=${movieId}`);
        const data = await response.json();

        if (data.status === 'success') {
            watchlistBtn.textContent = data.action === 'added' ? '✅' : '➕';
        } else {
            alert(data.message);
        }
    } catch (error) {
        console.error('Watchlist error:', error);
    }
}

async function loadWatchlistStatuses() {
    const movieIds = <?= json_encode(array_column($movies, 'id')) ?>;

    for (const movieId of movieIds) {
        const btn = document.getElementById(`watchlist-btn-${movieId}`);
        try {
            const res = await fetch(`api/watchlist_status.php?movie_id=${movieId}`);
            const data = await res.json();
            if (data.status === 'success') {
                btn.textContent = data.in_watchlist ? '✅' : '➕';
            }
        } catch (error) {
            console.error('Status error:', error);
        }
    }
}

document.addEventListener('DOMContentLoaded', loadWatchlistStatuses);
</script>
