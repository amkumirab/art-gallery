<?php
/**
 * admin/categories.php - CRUD for categories
 */
require_once __DIR__ . '/../includes/helpers.php';
require_admin();

$categoryModel = new Category();

// Handle delete
if (isset($_GET['id'], $_GET['delete'])) {
    $categoryModel->delete((int)$_GET['id']);
    set_flash('success', 'Category deleted.');
    redirect(BASE_URL . '/admin/categories.php');
}

// Handle edit
$editCat = null;
if (isset($_GET['edit'])) {
    $editCat = $categoryModel->getById((int)$_GET['edit']);
}

// Handle create/update POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'name'        => trim($_POST['name'] ?? ''),
        'description' => trim($_POST['description'] ?? ''),
    ];
    if (empty($data['name'])) {
        set_flash('error', 'Category name is required.');
    } else {
        if (!empty($_POST['edit_id'])) {
            $categoryModel->update((int)$_POST['edit_id'], $data);
            set_flash('success', 'Category updated.');
        } else {
            $categoryModel->create($data);
            set_flash('success', 'Category created.');
        }
        redirect(BASE_URL . '/admin/categories.php');
    }
}

$categories = $categoryModel->getAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories &middot; Admin</title>
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
            <li><a href="<?= BASE_URL ?>/admin/categories.php" class="active">Categories</a></li>
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

    <h1>Manage Categories</h1>

    <div class="admin-form" style="margin-bottom:var(--space-md);">
        <h3 style="margin-bottom:var(--space-sm);"><?= $editCat ? 'Edit Category' : 'Add New Category' ?></h3>
        <form method="post" action="">
            <?php if ($editCat): ?>
                <input type="hidden" name="edit_id" value="<?= (int)$editCat['id'] ?>">
            <?php endif; ?>
            <div class="form-group">
                <label>Category Name *</label>
                <input type="text" name="name" value="<?= sanitize($editCat['name'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="3"><?= sanitize($editCat['description'] ?? '') ?></textarea>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary btn-sm"><?= $editCat ? 'Update' : 'Add Category' ?></button>
                <?php if ($editCat): ?>
                    <a href="<?= BASE_URL ?>/admin/categories.php" class="btn btn-sm">Cancel</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr><th>ID</th><th>Name</th><th>Description</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $c): ?>
                    <tr>
                        <td><?= (int)$c['id'] ?></td>
                        <td><strong><?= sanitize($c['name']) ?></strong></td>
                        <td><?= sanitize($c['description'] ?? '—') ?></td>
                        <td class="actions">
                            <a href="<?= BASE_URL ?>/admin/categories.php?edit=<?= (int)$c['id'] ?>" class="btn btn-sm">Edit</a>
                            <a href="<?= BASE_URL ?>/admin/categories.php?id=<?= (int)$c['id'] ?>&delete=1" class="btn btn-sm btn-danger delete-confirm">Delete</a>
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
