<?php
/**
 * gallery.php - Browse all artworks with live search/filter (jQuery AJAX)
 * Chapter 3 (semantic HTML), Chapter 7 (flexbox, responsive), Chapter 10 (jQuery AJAX)
 */
require_once __DIR__ . '/includes/helpers.php';

$categoryModel = new Category();
$artistModel   = new Artist();
$categories    = $categoryModel->getAll();
$artists       = $artistModel->getAll();

$page_title  = 'Gallery';
$active_nav  = 'gallery';
include __DIR__ . '/includes/header.php';
?>

<?php if ($msg = get_flash('success')): ?>
    <div class="container"><div class="flash flash-success mt-md"><?= sanitize($msg) ?></div></div>
<?php endif; ?>

<div class="container">

    <div class="section-title"><h1>Art Gallery</h1></div>

    <!-- Toolbar: search, filters, sort (Chapter 5: forms; Chapter 10: AJAX targets) -->
    <div class="gallery-toolbar">
        <div class="gallery-filters">
            <label>Search:</label>
            <input type="search" id="search-input" placeholder="Title or artist..." autocomplete="off">

            <label>Category:</label>
            <select id="filter-category">
                <option value="">All Categories</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= (int)$cat['id'] ?>" <?= (isset($_GET['category_id']) && $_GET['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                        <?= sanitize($cat['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Artist:</label>
            <select id="filter-artist">
                <option value="">All Artists</option>
                <?php foreach ($artists as $art): ?>
                    <option value="<?= (int)$art['id'] ?>">
                        <?= sanitize($art['first_name'] . ' ' . $art['last_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Sort:</label>
            <select id="filter-sort">
                <option value="newest">Newest First</option>
                <option value="title">Title A-Z</option>
                <option value="oldest">Year (Old)</option>
                <option value="year_desc">Year (New)</option>
                <option value="price_low">Price (Low)</option>
                <option value="price_high">Price (High)</option>
            </select>
        </div>
    </div>

    <!-- Artwork grid populated by AJAX (Chapter 10: $.get, DOM creation) -->
    <div class="artwork-grid" id="artwork-grid">
        <div class="loading">Loading artworks...</div>
    </div>

</div>

<?php include __DIR__ . '/includes/footer.php'; ?>

<!-- gallery.js must load AFTER jQuery (loaded in footer.php) -->
<script src="<?= BASE_URL ?>/assets/js/gallery.js"></script>
