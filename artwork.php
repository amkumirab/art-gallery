<?php
/**
 * artwork.php - Single artwork detail page
 *
 * Chapter 3 (figure/figcaption, details/summary), Chapter 5 (review form),
 * Chapter 8 (quiz JS), Chapter 10 (favorite AJAX toggle).
 */
require_once __DIR__ . '/includes/helpers.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    redirect(BASE_URL . '/gallery.php');
}

$artworkModel = new Artwork();
$reviewModel  = new Review();
$artwork = $artworkModel->getById((int)$_GET['id']);

if (!$artwork) {
    set_flash('error', 'Artwork not found.');
    redirect(BASE_URL . '/gallery.php');
}

$reviews   = $reviewModel->getByArtwork($artwork['id']);
$avgRating = $reviewModel->getAverageRating($artwork['id']);

// Check if current user has favorited this
$isFavorited = false;
if (is_logged_in()) {
    $favModel = new Favorite();
    $isFavorited = $favModel->exists($_SESSION['user_id'], $artwork['id']);
}

// Handle review submission (Chapter 12: $_POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && is_logged_in()) {
    $rating  = (int)($_POST['rating'] ?? 0);
    $comment = trim($_POST['comment'] ?? '');
    if ($rating >= 1 && $rating <= 5) {
        $reviewModel->add($_SESSION['user_id'], $artwork['id'], $rating, $comment);
        set_flash('success', 'Your review has been posted!');
        // Redirect to avoid double-submit
        header('Location: ' . $_SERVER['REQUEST_URI']);
        exit;
    }
}

$page_title  = $artwork['title'];
$active_nav  = 'gallery';
include __DIR__ . '/includes/header.php';
?>

<?php if ($msg = get_flash('success')): ?>
    <div class="container"><div class="flash flash-success mt-md"><?= sanitize($msg) ?></div></div>
<?php endif; ?>

<div class="container">

    <a href="<?= BASE_URL ?>/gallery.php" class="back-link">&larr; Back to Gallery</a>

    <div class="artwork-detail">
        <!-- Left: Image (Chapter 3: figure/figcaption) -->
        <div class="detail-image">
            <figure>
                <img src="<?= BASE_URL ?>/assets/uploads/<?= sanitize($artwork['image_filename']) ?>"
                     alt="<?= sanitize($artwork['title']) ?>"
                     onerror="this.src='<?= BASE_URL ?>/assets/uploads/no-image.jpg'">
                <figcaption class="text-muted" style="margin-top:0.5rem; font-size:0.9rem;">
                    <?= sanitize($artwork['title']) ?> &mdash; <?= sanitize($artwork['artist_first'] . ' ' . $artwork['artist_last']) ?>
                </figcaption>
            </figure>
        </div>

        <!-- Right: Info -->
        <div class="detail-info">
            <h1><?= sanitize($artwork['title']) ?></h1>

            <p class="artist-name" style="font-size:1.1rem; margin-bottom:0.5rem;">
                by <a href="<?= BASE_URL ?>/artists.php" style="color:var(--color-accent-d)">
                    <?= sanitize($artwork['artist_first'] . ' ' . $artwork['artist_last']) ?>
                </a>
            </p>

            <?php if ($avgRating): ?>
                <p class="stars">
                    <?= str_repeat('&#9733;', (int)round($avgRating)) ?>
                    <span class="empty"><?= str_repeat('&#9733;', 5 - (int)round($avgRating)) ?></span>
                    <span class="text-muted" style="font-size:0.85rem;">(<?= $avgRating ?>)</span>
                </p>
            <?php endif; ?>

            <!-- Metadata list -->
            <ul class="detail-meta">
                <?php if ($artwork['year']): ?>
                    <li><span class="label">Year</span><span><?= (int)$artwork['year'] ?></span></li>
                <?php endif; ?>
                <?php if ($artwork['medium']): ?>
                    <li><span class="label">Medium</span><span><?= sanitize($artwork['medium']) ?></span></li>
                <?php endif; ?>
                <?php if ($artwork['dimensions']): ?>
                    <li><span class="label">Dimensions</span><span><?= sanitize($artwork['dimensions']) ?></span></li>
                <?php endif; ?>
                <?php if ($artwork['category_name']): ?>
                    <li><span class="label">Category</span><span><?= sanitize($artwork['category_name']) ?></span></li>
                <?php endif; ?>
                <li><span class="label">Price</span><span class="price" style="font-size:1.2rem"><?= format_price($artwork['price']) ?></span></li>
            </ul>

            <!-- Description -->
            <?php if ($artwork['description']): ?>
                <p style="margin-top:var(--space-sm);"><?= nl2br(sanitize($artwork['description'])) ?></p>
            <?php endif; ?>

            <!-- Favorite button (Chapter 10: AJAX toggle) -->
            <?php if (is_logged_in()): ?>
                <button class="favorite-btn <?= $isFavorited ? 'is-favorited' : '' ?>"
                        id="fav-btn" data-artwork-id="<?= (int)$artwork['id'] ?>">
                    <span class="heart">&#9829;</span>
                    <span class="fav-text"><?= $isFavorited ? 'Saved to Favorites' : 'Add to Favorites' ?></span>
                </button>
            <?php else: ?>
                <p class="text-muted mt-sm"><a href="<?= BASE_URL ?>/login.php">Login</a> to save favorites.</p>
            <?php endif; ?>

            <!-- Artist bio (Chapter 3: details/summary) -->
            <?php if ($artwork['artist_bio']): ?>
                <details style="margin-top:var(--space-md);">
                    <summary style="cursor:pointer; font-weight:600; color:var(--color-accent-d);">
                        About the Artist
                    </summary>
                    <p style="margin-top:0.5rem;">
                        <?= sanitize($artwork['artist_first']) ?> <?= sanitize($artwork['artist_last']) ?>
                        (<?= (int)$artwork['artist_birth'] ?><?= $artwork['artist_death'] ? '–' . (int)$artwork['artist_death'] : '–present' ?>),
                        <?= sanitize($artwork['artist_nat']) ?>.
                    </p>
                    <p><?= nl2br(sanitize($artwork['artist_bio'])) ?></p>
                </details>
            <?php endif; ?>
        </div>
    </div>

    <!-- Quiz section (gamification — Chapter 8: JS objects/arrays) -->
    <section style="margin-top:var(--space-lg); background:var(--color-surface); border:1px solid var(--color-border); border-radius:var(--radius); padding:var(--space-md);">
        <h2>&#128218; Test Your Knowledge</h2>
        <p class="text-muted" style="margin-bottom:var(--space-sm);">How well do you know this artwork and its artist?</p>
        <div id="quiz-container"></div>
        <div id="quiz-result" style="margin-top:var(--space-sm);"></div>
    </section>

    <!-- Reviews section -->
    <section class="reviews-section">
        <h2>Reviews (<?= count($reviews) ?>)</h2>

        <?php if (is_logged_in()): ?>
            <div style="background:var(--color-surface); border:1px solid var(--color-border); border-radius:var(--radius); padding:var(--space-md); margin-bottom:var(--space-md);">
                <h3>Write a Review</h3>
                <form method="post" action="" id="review-form">
                    <div class="form-group">
                        <label>Rating</label>
                        <div class="rating-input">
                            <?php for ($i = 5; $i >= 1; $i--): ?>
                                <input type="radio" id="star<?= $i ?>" name="rating" value="<?= $i ?>" <?= $i === 5 ? 'checked' : '' ?>>
                                <label for="star<?= $i ?>">&#9733;</label>
                            <?php endfor; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="comment">Comment</label>
                        <textarea id="comment" name="comment" rows="3" placeholder="Share your thoughts on this artwork..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">Submit Review</button>
                </form>
            </div>
        <?php else: ?>
            <p class="text-muted"><a href="<?= BASE_URL ?>/login.php">Login</a> to write a review.</p>
        <?php endif; ?>

        <div>
            <?php if (empty($reviews)): ?>
                <p class="text-muted">No reviews yet. Be the first to share your thoughts!</p>
            <?php else: ?>
                <?php foreach ($reviews as $r): ?>
                    <div class="review-item">
                        <div class="review-header">
                            <span class="review-author"><?= sanitize($r['username']) ?></span>
                            <span class="review-date"><?= date('M j, Y', strtotime($r['created_at'])) ?></span>
                        </div>
                        <div class="stars" style="font-size:0.9rem;">
                            <?= str_repeat('&#9733;', (int)$r['rating']) ?>
                            <span class="empty"><?= str_repeat('&#9733;', 5 - (int)$r['rating']) ?></span>
                        </div>
                        <?php if ($r['comment']): ?>
                            <p style="margin-top:0.3rem;"><?= sanitize($r['comment']) ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>

</div>

<?php if (is_logged_in()): ?>
    <script src="<?= BASE_URL ?>/assets/js/favorite.js"></script>
<?php endif; ?>
<script src="<?= BASE_URL ?>/assets/js/quiz.js"></script>
<?php include __DIR__ . '/includes/footer.php'; ?>
