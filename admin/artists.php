<?php
/**
 * admin/artists.php - CRUD for artists
 * Chapter 5 (tables), Chapter 12 ($_GET, $_POST)
 */
require_once __DIR__ . '/../includes/helpers.php';
require_admin();

$artistModel = new Artist();

// Handle delete
if (isset($_GET['id'], $_GET['delete'])) {
    $artistModel->delete((int)$_GET['id']);
    set_flash('success', 'Artist deleted.');
    redirect(BASE_URL . '/admin/artists.php');
}

// Handle edit inline
$editArtist = null;
if (isset($_GET['edit'])) {
    $editArtist = $artistModel->getById((int)$_GET['edit']);
}

// Handle create/update POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'first_name'  => trim($_POST['first_name'] ?? ''),
        'last_name'   => trim($_POST['last_name'] ?? ''),
        'birth_year'  => $_POST['birth_year'] ?? '',
        'death_year'  => $_POST['death_year'] ?? '',
        'nationality' => trim($_POST['nationality'] ?? ''),
        'biography'   => trim($_POST['biography'] ?? ''),
    ];
    if (empty($data['last_name'])) {
        set_flash('error', 'Last name is required.');
    } else {
        if (!empty($_POST['edit_id'])) {
            $artistModel->update((int)$_POST['edit_id'], $data);
            set_flash('success', 'Artist updated.');
        } else {
            $artistModel->create($data);
            set_flash('success', 'Artist created.');
        }
        redirect(BASE_URL . '/admin/artists.php');
    }
}

$artists = $artistModel->getAll();

// Reusable admin layout helper (inline to avoid dependency issues)
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Artists &middot; Admin</title>
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
            <li><a href="<?= BASE_URL ?>/admin/artists.php" class="active">Artists</a></li>
            <li><a href="<?= BASE_URL ?>/admin/categories.php">Categories</a></li>
        </ul>
        <h4>Users & Content</h4>
        <ul>
            <li><a href="<?= BASE_URL ?>/admin/users.php">Users</a></li>
            <li><a href="<?= BASE_URL ?>/admin/reviews.php">Reviews</a></li>
        </ul>
    </aside>
    <div class="admin-main">

    <?php if ($msg = get_flash('success')): ?>
        <div class="flash flash-success"><?= sanitize($msg) ?></div>
    <?php endif; ?>
    <?php if ($msg = get_flash('error')): ?>
        <div class="flash flash-error"><?= sanitize($msg) ?></div>
    <?php endif; ?>

    <h1>Manage Artists</h1>

    <!-- Inline add/edit form -->
    <div class="admin-form" style="margin-bottom:var(--space-md);">
        <h3 style="margin-bottom:var(--space-sm);"><?= $editArtist ? 'Edit Artist' : 'Add New Artist' ?></h3>
        <form method="post" action="">
            <?php if ($editArtist): ?>
                <input type="hidden" name="edit_id" value="<?= (int)$editArtist['id'] ?>">
            <?php endif; ?>
            <div class="row">
                <div class="form-group">
                    <label>First Name</label>
                    <input type="text" name="first_name" value="<?= sanitize($editArtist['first_name'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Last Name *</label>
                    <input type="text" name="last_name" value="<?= sanitize($editArtist['last_name'] ?? '') ?>" required>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <label>Birth Year</label>
                    <input type="number" name="birth_year" value="<?= (int)($editArtist['birth_year'] ?? '') ?: '' ?>" min="0" max="2100">
                </div>
                <div class="form-group">
                    <label>Death Year</label>
                    <input type="number" name="death_year" value="<?= (int)($editArtist['death_year'] ?? '') ?: '' ?>" min="0" max="2100">
                </div>
                <div class="form-group">
                    <label>Nationality</label>
                    <input type="text" name="nationality" value="<?= sanitize($editArtist['nationality'] ?? '') ?>">
                </div>
            </div>
            <div class="form-group">
                <label>Biography</label>
                <textarea name="biography" rows="3"><?= sanitize($editArtist['biography'] ?? '') ?></textarea>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary btn-sm"><?= $editArtist ? 'Update' : 'Add Artist' ?></button>
                <?php if ($editArtist): ?>
                    <a href="<?= BASE_URL ?>/admin/artists.php" class="btn btn-sm">Cancel</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Artists table -->
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr><th>Name</th><th>Years</th><th>Nationality</th><th>Artworks</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php foreach ($artists as $a): ?>
                    <tr>
                        <td><strong><?= sanitize($a['first_name'] . ' ' . $a['last_name']) ?></strong></td>
                        <td>
                            <?php if ($a['birth_year']): ?>
                                <?= (int)$a['birth_year'] ?><?= $a['death_year'] ? ' – ' . (int)$a['death_year'] : '' ?>
                            <?php else: ?> — <?php endif; ?>
                        </td>
                        <td><?= sanitize($a['nationality'] ?? '—') ?></td>
                        <td><?= (int)$a['artwork_count'] ?></td>
                        <td class="actions">
                            <a href="<?= BASE_URL ?>/admin/artists.php?edit=<?= (int)$a['id'] ?>" class="btn btn-sm">Edit</a>
                            <a href="<?= BASE_URL ?>/admin/artists.php?id=<?= (int)$a['id'] ?>&delete=1" class="btn btn-sm btn-danger delete-confirm">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    </div>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="<?= BASE_URL ?>/assets/js/admin.js"></script>
</body>
</html>
