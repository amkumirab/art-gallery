<?php
/**
 * admin/users.php - View, promote/demote, and delete users
 */
require_once __DIR__ . '/../includes/helpers.php';
require_admin();

$userModel = new User();

// Handle role toggle
if (isset($_GET['toggle_role'])) {
    $userModel->toggleRole((int)$_GET['toggle_role']);
    set_flash('success', 'User role updated.');
    redirect(BASE_URL . '/admin/users.php');
}

// Handle delete
if (isset($_GET['id'], $_GET['delete'])) {
    $userId = (int)$_GET['id'];
    // Prevent deleting yourself
    if ($userId === $_SESSION['user_id']) {
        set_flash('error', 'You cannot delete your own account.');
    } else {
        $userModel->delete($userId);
        set_flash('success', 'User deleted.');
    }
    redirect(BASE_URL . '/admin/users.php');
}

$users = $userModel->getAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users &middot; Admin</title>
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
            <li><a href="<?= BASE_URL ?>/admin/users.php" class="active">Users</a></li>
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

    <h1>Manage Users</h1>

    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr><th>ID</th><th>Username</th><th>Email</th><th>Role</th><th>Registered</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <td><?= (int)$u['id'] ?></td>
                        <td><strong><?= sanitize($u['username']) ?></strong></td>
                        <td><?= sanitize($u['email']) ?></td>
                        <td>
                            <span class="role-badge <?= sanitize($u['role']) ?>"><?= sanitize($u['role']) ?></span>
                        </td>
                        <td><?= date('M j, Y', strtotime($u['created_at'])) ?></td>
                        <td class="actions">
                            <?php if ($u['id'] !== $_SESSION['user_id']): ?>
                                <a href="<?= BASE_URL ?>/admin/users.php?toggle_role=<?= (int)$u['id'] ?>" class="btn btn-sm">
                                    <?= $u['role'] === 'admin' ? 'Demote' : 'Promote' ?>
                                </a>
                                <a href="<?= BASE_URL ?>/admin/users.php?id=<?= (int)$u['id'] ?>&delete=1"
                                   class="btn btn-sm btn-danger delete-confirm">Delete</a>
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
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
