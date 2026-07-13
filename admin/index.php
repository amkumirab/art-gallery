<?php
/**
 * admin/index.php - Admin dashboard with statistics and recent activity
 * Chapter 5 (styled tables), Chapter 8 (JS counters)
 */
require_once __DIR__ . '/../includes/helpers.php';
require_admin();

$artworkModel  = new Artwork();
$artistModel   = new Artist();
$categoryModel = new Category();
$userModel     = new User();
$reviewModel   = new Review();

$stats = [
    'artworks'  => $artworkModel->count(),
    'artists'   => $artistModel->count(),
    'categories'=> $categoryModel->count(),
    'users'     => $userModel->count(),
    'reviews'   => $reviewModel->count(),
];
$recent = $artworkModel->getRecent(5);

if ($msg = get_flash('success')): ?>
    <?php /* flash will be shown inside layout */ ?>
<?php endif;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard &middot; Admin</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/main.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/responsive.css">
</head>
<body class="admin-body">

<header class="admin-header">
    <div class="container" style="max-width:var(--max-width); padding:0 var(--space-md);">
        <a href="<?= BASE_URL ?>/admin/index.php" class="logo">Aurelia <span>Admin</span></a>
        <div class="user-info">
            <span>Logged in as <strong><?= sanitize($_SESSION['username']) ?></strong></span>
            <a href="<?= BASE_URL ?>/index.php" class="btn btn-sm">View Site</a>
            <a href="<?= BASE_URL ?>/admin/logout.php" class="btn btn-sm btn-danger">Logout</a>
        </div>
    </div>
</header>

<div class="admin-layout">
    <!-- Sidebar (Chapter 7: flex layout) -->
    <aside class="admin-sidebar">
        <h4>Management</h4>
        <ul>
            <li><a href="<?= BASE_URL ?>/admin/index.php" class="active">Dashboard</a></li>
            <li><a href="<?= BASE_URL ?>/admin/artworks.php">Artworks</a></li>
            <li><a href="<?= BASE_URL ?>/admin/artists.php">Artists</a></li>
            <li><a href="<?= BASE_URL ?>/admin/categories.php">Categories</a></li>
        </ul>
        <h4>Users & Content</h4>
        <ul>
            <li><a href="<?= BASE_URL ?>/admin/users.php">Users</a></li>
            <li><a href="<?= BASE_URL ?>/admin/reviews.php">Reviews</a></li>
        </ul>
    </aside>

    <!-- Main content -->
    <div class="admin-main">
        <?php if ($msg = get_flash('success')): ?>
            <div class="flash flash-success"><?= sanitize($msg) ?></div>
        <?php endif; ?>

        <h1>Dashboard</h1>

        <!-- Stat cards (Chapter 8: JS animates the numbers) -->
        <div class="stat-grid">
            <div class="stat-card">
                <div class="stat-number" data-count="<?= $stats['artworks'] ?>">0</div>
                <div class="stat-label">Artworks</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" data-count="<?= $stats['artists'] ?>">0</div>
                <div class="stat-label">Artists</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" data-count="<?= $stats['categories'] ?>">0</div>
                <div class="stat-label">Categories</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" data-count="<?= $stats['users'] ?>">0</div>
                <div class="stat-label">Users</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" data-count="<?= $stats['reviews'] ?>">0</div>
                <div class="stat-label">Reviews</div>
            </div>
        </div>

        <!-- Recent artworks table (Chapter 5: table structure) -->
        <h2 style="margin-top:var(--space-md);">Recent Additions</h2>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Artist</th>
                        <th>Year</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($recent)): ?>
                        <tr><td colspan="4" class="text-center text-muted">No artworks yet.</td></tr>
                    <?php else: ?>
                        <?php foreach ($recent as $art): ?>
                            <tr>
                                <td><a href="<?= BASE_URL ?>/admin/artwork-form.php?id=<?= (int)$art['id'] ?>"><?= sanitize($art['title']) ?></a></td>
                                <td><?= sanitize($art['artist_first'] . ' ' . $art['artist_last']) ?></td>
                                <td><?= $art['year'] ? (int)$art['year'] : '—' ?></td>
                                <td><?= format_price($art['price']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="<?= BASE_URL ?>/assets/js/admin.js"></script>
</body>
</html>
