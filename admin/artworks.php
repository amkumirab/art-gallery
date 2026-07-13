<?php
/**
 * admin/artworks.php - List, edit, delete artworks (admin CRUD)
 * Chapter 5 (tables), Chapter 10 (jQuery confirm delete, AJAX)
 */
require_once __DIR__ . '/../includes/helpers.php';
require_admin();

$artworkModel  = new Artwork();
$categoryModel = new Category();

// Handle delete via GET id&delete=1 (with confirmation from JS)
if (isset($_GET['id'], $_GET['delete'])) {
    $artworkModel->delete((int)$_GET['id']);
    set_flash('success', 'Artwork deleted.');
    redirect(BASE_URL . '/admin/artworks.php');
}

$artworks = $artworkModel->getAll();

// Helper to generate admin page skeleton (keeps code DRY)
function admin_page_start($title, $activePage) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= sanitize($title) ?> &middot; Admin</title>
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
                <li><a href="<?= BASE_URL ?>/admin/artworks.php" class="<?= $activePage === 'artworks' ? 'active' : '' ?>">Artworks</a></li>
                <li><a href="<?= BASE_URL ?>/admin/artists.php" class="<?= $activePage === 'artists' ? 'active' : '' ?>">Artists</a></li>
                <li><a href="<?= BASE_URL ?>/admin/categories.php" class="<?= $activePage === 'categories' ? 'active' : '' ?>">Categories</a></li>
            </ul>
            <h4>Users & Content</h4>
            <ul>
                <li><a href="<?= BASE_URL ?>/admin/users.php" class="<?= $activePage === 'users' ? 'active' : '' ?>">Users</a></li>
                <li><a href="<?= BASE_URL ?>/admin/reviews.php" class="<?= $activePage === 'reviews' ? 'active' : '' ?>">Reviews</a></li>
            </ul>
        </aside>
        <div class="admin-main">
    <?php
}

function admin_page_end() {
    ?>
        </div><!-- /.admin-main -->
    </div><!-- /.admin-layout -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/admin.js"></script>
    </body>
    </html>
    <?php
}

// Move these to a shared file later — for now, define before use

// Start the admin page
admin_page_start('Manage Artworks', 'artworks');

if ($msg = get_flash('success')): ?>
    <div class="flash flash-success"><?= sanitize($msg) ?></div>
<?php endif; ?>

<h1>Manage Artworks</h1>

<div class="table-header">
    <span class="text-muted"><?= count($artworks) ?> artwork<?= count($artworks) !== 1 ? 's' : '' ?></span>
    <a href="<?= BASE_URL ?>/admin/artwork-form.php" class="btn btn-primary btn-sm">+ Add Artwork</a>
</div>

<div class="admin-table-wrap">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Image</th>
                <th>Title</th>
                <th>Artist</th>
                <th>Category</th>
                <th>Year</th>
                <th>Price</th>
                <th>Featured</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($artworks)): ?>
                <tr><td colspan="8" class="text-center text-muted">No artworks found.</td></tr>
            <?php else: ?>
                <?php foreach ($artworks as $art): ?>
                    <tr>
                        <td>
                            <img class="thumb" src="<?= BASE_URL ?>/assets/uploads/<?= sanitize($art['image_filename']) ?>"
                                 alt="" onerror="this.src='<?= BASE_URL ?>/assets/uploads/no-image.jpg'">
                        </td>
                        <td><?= sanitize($art['title']) ?></td>
                        <td><?= sanitize($art['artist_first'] . ' ' . $art['artist_last']) ?></td>
                        <td><?= sanitize($art['category_name'] ?? '—') ?></td>
                        <td><?= $art['year'] ? (int)$art['year'] : '—' ?></td>
                        <td><?= format_price($art['price']) ?></td>
                        <td><?= $art['is_featured'] ? '&#9733;' : '—' ?></td>
                        <td class="actions">
                            <a href="<?= BASE_URL ?>/admin/artwork-form.php?id=<?= (int)$art['id'] ?>" class="btn btn-sm">Edit</a>
                            <a href="<?= BASE_URL ?>/admin/artworks.php?id=<?= (int)$art['id'] ?>&delete=1"
                               class="btn btn-sm btn-danger delete-confirm">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php admin_page_end(); ?>
