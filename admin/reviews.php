<?php
/**
 * admin/reviews.php - Moderate user reviews (view + delete)
 */
require_once __DIR__ . '/../includes/helpers.php';
require_admin();

$reviewModel = new Review();

// Handle delete
if (isset($_GET['id'], $_GET['delete'])) {
    $reviewModel->delete((int)$_GET['id']);
    set_flash('success', 'Review deleted.');
    redirect(BASE_URL . '/admin/reviews.php');
}

$reviews = $reviewModel->getAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moderate Reviews &middot; Admin</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/main.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/forms.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/responsive.css">
</head>
<body class="admin-body">
<header class="admin-header">
    <div class="container" style="max-width:var(--max-width); padding:0 var(--space-md);">
        <a href="<?= BASE_URL ?>/admin/index.php" class="logo">Aurelia <span>Admin</span></a>
        <div class="user-info">
            <span><strong><?= sanitize($_SESSION['username']) ?></strong></span>
            <a href="<?= BASE_URL ?>/admin/logout.php" class="btn btn-sm btn-danger">Logout</a>
        </div>
    </div>
</header>
<div class="admin-layout">
    <aside class="admin-sidebar">
        <h4>Management</h4>
        <ul>
            <li><a href="<?= BASE_URL ?>/admin/index.php">Dashboard</a></li>
            <li><a href="<?= BASE_URL ?>/admin/artworks.php">Artworks</a></li>
            <li><a href="<?= BASE_URL ?>/admin/artists.php">Artists</a></li>
            <li><a href="<?= BASE_URL ?>/admin/categories.php">Categories</a></li>
        </ul>
        <h4>Users & Content</h4>
        <ul>
            <li><a href="<?= BASE_URL ?>/admin/users.php">Users</a></li>
            <li><a href="<?= BASE_URL ?>/admin/reviews.php" class="active">Reviews</a></li>
        </ul>
    </aside>
    <div class="admin-main">

    <?php if ($msg = get_flash('success')): ?>
        <div class="flash flash-success"><?= sanitize($msg) ?></div>
    <?php endif; ?>

    <h1>Moderate Reviews</h1>

    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr><th>ID</th><th>Artwork</th><th>User</th><th>Rating</th><th>Comment</th><th>Date</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php if (empty($reviews)): ?>
                    <tr><td colspan="7" class="text-center text-muted">No reviews yet.</td></tr>
                <?php else: ?>
                    <?php foreach ($reviews as $r): ?>
                        <tr>
                            <td><?= (int)$r['id'] ?></td>
                            <td><?= sanitize($r['artwork_title']) ?></td>
                            <td><?= sanitize($r['username']) ?></td>
                            <td><span class="stars" style="font-size:0.85rem;"><?= str_repeat('&#9733;', (int)$r['rating']) ?></span></td>
                            <td><?= sanitize(mb_strimwidth($r['comment'], 0, 60, '...')) ?></td>
                            <td><?= date('M j, Y', strtotime($r['created_at'])) ?></td>
                            <td class="actions">
                                <a href="<?= BASE_URL ?>/admin/reviews.php?id=<?= (int)$r['id'] ?>&delete=1"
                                   class="btn btn-sm btn-danger delete-confirm">Delete</a>
                            </td>
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
