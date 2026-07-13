<?php
/**
 * artists.php - Browse all artists
 * Chapter 3 (semantic HTML), Chapter 7 (flexbox grid)
 */
require_once __DIR__ . '/includes/helpers.php';

$artistModel = new Artist();
$artists = $artistModel->getAll();

$page_title  = 'Artists';
$active_nav  = 'artists';
include __DIR__ . '/includes/header.php';
?>

<div class="container">
    <div class="section-title"><h1>Our Artists</h1></div>

    <?php if (empty($artists)): ?>
        <div class="empty-state">
            <h3>No artists yet</h3>
            <p>The administrator hasn't added any artists.</p>
        </div>
    <?php else: ?>
        <div class="artist-grid">
            <?php foreach ($artists as $a): ?>
                <article class="artist-card">
                    <h3><?= sanitize($a['first_name'] . ' ' . $a['last_name']) ?></h3>
                    <p class="years">
                        <?php if ($a['birth_year']): ?>
                            <?= (int)$a['birth_year'] ?><?= $a['death_year'] ? ' – ' . (int)$a['death_year'] : ' – present' ?>
                        <?php endif; ?>
                    </p>
                    <?php if ($a['nationality']): ?>
                        <span class="nationality"><?= sanitize($a['nationality']) ?></span>
                    <?php endif; ?>
                    <p class="text-muted mt-sm" style="font-size:0.85rem;">
                        <?= $a['artwork_count'] ?> artwork<?= $a['artwork_count'] != 1 ? 's' : '' ?> in collection
                    </p>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
