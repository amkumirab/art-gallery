<?php
/**
 * admin/artwork-form.php - Add/Edit artwork form
 * Chapter 5 (forms, file upload), Chapter 12 ($_POST, $_FILES)
 */
require_once __DIR__ . '/../includes/helpers.php';
require_admin();

$artworkModel  = new Artwork();
$artistModel   = new Artist();
$categoryModel = new Category();

$artists    = $artistModel->getAll();
$categories = $categoryModel->getAll();

// Are we editing?
$isEdit = isset($_GET['id']) && is_numeric($_GET['id']);
$artwork = $isEdit ? $artworkModel->getById((int)$_GET['id']) : null;

if ($isEdit && !$artwork) {
    set_flash('error', 'Artwork not found.');
    redirect(BASE_URL . '/admin/artworks.php');
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data
    $data = [
        'title'       => trim($_POST['title'] ?? ''),
        'artist_id'   => $_POST['artist_id'] ?? '',
        'category_id' => $_POST['category_id'] ?? '',
        'year'        => $_POST['year'] ?? '',
        'medium'      => trim($_POST['medium'] ?? ''),
        'dimensions'  => trim($_POST['dimensions'] ?? ''),
        'description' => trim($_POST['description'] ?? ''),
        'price'       => $_POST['price'] ?? '',
        'is_featured' => !empty($_POST['is_featured']),
    ];

    // Validate
    if (empty($data['title'])) $errors[] = 'Title is required.';

    // Handle image upload (Chapter 12: $_FILES)
    $filename = $artwork['image_filename'] ?? 'placeholder.jpg';
    if (!empty($_FILES['image']['name'])) {
        if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'File upload error. Please try again.';
        } else {
            $fileType = mime_content_type($_FILES['image']['tmp_name']);
            if (!in_array($fileType, ALLOWED_IMAGE_TYPES)) {
                $errors[] = 'Only JPG, PNG, and WebP images are allowed.';
            } elseif ($_FILES['image']['size'] > MAX_FILE_SIZE) {
                $errors[] = 'Maximum file size is 5 MB.';
            } else {
                $ext  = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $filename = uniqid('art_') . '.' . $ext;
                move_uploaded_file($_FILES['image']['tmp_name'], UPLOAD_DIR . $filename);
            }
        }
    }
    $data['image_filename'] = $filename;

    if (empty($errors)) {
        if ($isEdit) {
            $artworkModel->update((int)$_GET['id'], $data);
            set_flash('success', 'Artwork updated.');
        } else {
            $artworkModel->create($data);
            set_flash('success', 'Artwork created.');
        }
        redirect(BASE_URL . '/admin/artworks.php');
    }
}

// Pre-fill values for edit
$val = $artwork ?? ['title'=>'','artist_id'=>'','category_id'=>'','year'=>'','medium'=>'','dimensions'=>'','description'=>'','price'=>'','is_featured'=>false,'image_filename'=>'placeholder.jpg'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $isEdit ? 'Edit Artwork' : 'Add Artwork' ?> &middot; Admin</title>
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
            <li><a href="<?= BASE_URL ?>/admin/artworks.php" class="active">Artworks</a></li>
            <li><a href="<?= BASE_URL ?>/admin/artists.php">Artists</a></li>
            <li><a href="<?= BASE_URL ?>/admin/categories.php">Categories</a></li>
        </ul>
        <h4>Users & Content</h4>
        <ul>
            <li><a href="<?= BASE_URL ?>/admin/users.php">Users</a></li>
            <li><a href="<?= BASE_URL ?>/admin/reviews.php">Reviews</a></li>
        </ul>
    </aside>

    <div class="admin-main">
        <a href="<?= BASE_URL ?>/admin/artworks.php" class="back-link">&larr; Back to Artworks</a>
        <h1><?= $isEdit ? 'Edit Artwork' : 'Add New Artwork' ?></h1>

        <?php if (!empty($errors)): ?>
            <div class="flash flash-error">
                <ul style="margin:0; padding-left:1.2rem;">
                    <?php foreach ($errors as $e): ?><li><?= sanitize($e) ?></li><?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post" action="" enctype="multipart/form-data" class="admin-form form-horizontal">
            <div class="form-group">
                <label for="title">Title *</label>
                <input type="text" id="title" name="title" value="<?= sanitize($val['title']) ?>" required>
            </div>

            <div class="row">
                <div class="form-group">
                    <label for="artist_id">Artist</label>
                    <select id="artist_id" name="artist_id">
                        <option value="">— Select Artist —</option>
                        <?php foreach ($artists as $a): ?>
                            <option value="<?= (int)$a['id'] ?>" <?= ($val['artist_id'] == $a['id']) ? 'selected' : '' ?>>
                                <?= sanitize($a['first_name'] . ' ' . $a['last_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="category_id">Category</label>
                    <select id="category_id" name="category_id">
                        <option value="">— Select Category —</option>
                        <?php foreach ($categories as $c): ?>
                            <option value="<?= (int)$c['id'] ?>" <?= ($val['category_id'] == $c['id']) ? 'selected' : '' ?>>
                                <?= sanitize($c['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="form-group">
                    <label for="year">Year</label>
                    <input type="number" id="year" name="year" value="<?= (int)$val['year'] ?: '' ?>" min="0" max="2100">
                </div>
                <div class="form-group">
                    <label for="price">Price ($)</label>
                    <input type="number" id="price" name="price" value="<?= $val['price'] ?? '' ?>" step="0.01" min="0">
                </div>
            </div>

            <div class="row">
                <div class="form-group">
                    <label for="medium">Medium</label>
                    <input type="text" id="medium" name="medium" value="<?= sanitize($val['medium']) ?>" placeholder="Oil, Watercolor, Sculpture...">
                </div>
                <div class="form-group">
                    <label for="dimensions">Dimensions</label>
                    <input type="text" id="dimensions" name="dimensions" value="<?= sanitize($val['dimensions']) ?>" placeholder="100 x 80 cm">
                </div>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="4"><?= sanitize($val['description']) ?></textarea>
            </div>

            <div class="form-group">
                <label for="image">Image</label>
                <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/webp">
                <p class="hint">Max 5 MB. JPG, PNG, or WebP.</p>
                <?php if ($isEdit && $val['image_filename']): ?>
                    <div class="upload-preview">
                        <img src="<?= BASE_URL ?>/assets/uploads/<?= sanitize($val['image_filename']) ?>"
                             alt="Current" onerror="this.style.display='none'">
                    </div>
                <?php endif; ?>
            </div>

            <div class="form-check">
                <input type="checkbox" id="is_featured" name="is_featured" value="1"
                       <?= !empty($val['is_featured']) ? 'checked' : '' ?>>
                <label for="is_featured">Mark as featured</label>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Update Artwork' : 'Create Artwork' ?></button>
                <a href="<?= BASE_URL ?>/admin/artworks.php" class="btn">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="<?= BASE_URL ?>/assets/js/admin.js"></script>
</body>
</html>
