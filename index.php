<?php
/**
 * index.php - Home page
 *
 * Shows a hero banner, featured artworks grid, and category links.
 * Chapter 3 (semantic structure), Chapter 7 (hero, flexbox grid).
 */
require_once __DIR__ . '/includes/helpers.php';

$artworkModel = new Artwork();
$categoryModel = new Category();

$featured = $artworkModel->getFeatured(6);
$categories = $categoryModel->getAll();

$page_title = 'Home';
$active_nav = 'home';
include __DIR__ . '/includes/header.php';
?>

<!-- Flash message -->
<?php if ($msg = get_flash('success')): ?>
    <div class="container"><div class="flash flash-success mt-md"><?= sanitize($msg) ?></div></div>
<?php endif; ?>
<?php if ($msg = get_flash('error')): ?>
    <div class="container"><div class="flash flash-error mt-md"><?= sanitize($msg) ?></div></div>
<?php endif; ?>

<!-- Hero banner (Chapter 7: positioning, gradient) -->
<section class="hero">
    <div class="hero-content">
        <h1>Where Art Lives Forever</h1>
        <p>Discover timeless masterpieces from the world's greatest artists.</p>
        <a href="<?= BASE_URL ?>/gallery.php" class="btn">Explore the Gallery</a>
    </div>
</section>

<div class="container">

    <!-- Category quick-links -->
    <?php if (!empty($categories)): ?>
    <section class="mt-md">
        <div class="section-title"><h2>Browse by Category</h2></div>
        <div class="category-grid">
            <?php foreach ($categories as $cat): ?>
                <a href="<?= BASE_URL ?>/gallery.php?category_id=<?= (int)$cat['id'] ?>">
                    <?= sanitize($cat['name']) ?>
                </a>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- Featured artworks -->
    <section class="mt-md">
        <div class="section-title"><h2>Featured Masterpieces</h2></div>
        <?php if (empty($featured)): ?>
            <div class="empty-state">
                <h3>No artworks yet</h3>
                <p>The administrator hasn't added any featured artworks.</p>
            </div>
        <?php else: ?>
            <div class="artwork-grid">
                <?php foreach ($featured as $art): ?>
                    <article class="artwork-card">
                        <a href="<?= BASE_URL ?>/artwork.php?id=<?= (int)$art['id'] ?>" class="card-image">
                            <?php if (!empty($art['is_featured'])): ?>
                                <span class="featured-badge">Featured</span>
                            <?php endif; ?>
                            <img src="<?= BASE_URL ?>/assets/uploads/<?= sanitize($art['image_filename']) ?>"
                                 alt="<?= sanitize($art['title']) ?>"
                                 onerror="this.src='<?= BASE_URL ?>/assets/uploads/no-image.jpg'">
                        </a>
                        <div class="card-body">
                            <h3><a href="<?= BASE_URL ?>/artwork.php?id=<?= (int)$art['id'] ?>"><?= sanitize($art['title']) ?></a></h3>
                            <p class="artist-name"><?= sanitize($art['artist_first'] . ' ' . $art['artist_last']) ?></p>
                            <div class="card-meta">
                                <?php if (!empty($art['year'])): ?>
                                    <span class="text-muted"><?= (int)$art['year'] ?></span>
                                <?php endif; ?>
                                <span class="price"><?= format_price($art['price']) ?></span>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

    <div class="text-center mt-md">
        <a href="<?= BASE_URL ?>/gallery.php" class="btn btn-primary">View All Artworks</a>
    </div>

</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
