<?php
/**
 * favorites.php - User's saved artworks
 * Requires login (Chapter 12: $_SESSION)
 */
require_once __DIR__ . '/includes/helpers.php';
require_login(); // redirect to login if not authenticated

$favModel = new Favorite();
$favorites = $favModel->getByUser($_SESSION['user_id']);

$page_title  = 'My Favorites';
$active_nav  = 'favorites';
include __DIR__ . '/includes/header.php';
?>

<div class="container">
    <div class="section-title"><h1>My Favorites</h1></div>

    <?php if (empty($favorites)): ?>
        <div class="empty-state">
            <h3>No favorites yet</h3>
            <p>You haven't saved any artworks. <a href="<?= BASE_URL ?>/gallery.php">Browse the gallery</a> and click the heart icon!</p>
        </div>
    <?php else: ?>
        <div class="artwork-grid">
            <?php foreach ($favorites as $art): ?>
                <article class="artwork-card">
                    <a href="<?= BASE_URL ?>/artwork.php?id=<?= (int)$art['id'] ?>" class="card-image">
                        <img src="<?= BASE_URL ?>/assets/uploads/<?= sanitize($art['image_filename']) ?>"
                             alt="<?= sanitize($art['title']) ?>"
                             onerror="this.src='<?= BASE_URL ?>/assets/uploads/no-image.jpg'">
                    </a>
                    <div class="card-body">
                        <h3><a href="<?= BASE_URL ?>/artwork.php?id=<?= (int)$art['id'] ?>"><?= sanitize($art['title']) ?></a></h3>
                        <p class="artist-name"><?= sanitize($art['artist_first'] . ' ' . $art['artist_last']) ?></p>
                        <div class="card-meta">
                            <span class="text-muted"><?= (int)$art['year'] ?? '' ?></span>
                            <span class="price"><?= format_price($art['price']) ?></span>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
