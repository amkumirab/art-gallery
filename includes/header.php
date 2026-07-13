<?php
/**
 * header.php - Shared site header + opening HTML
 *
 * Included by every public-facing page. Demonstrates:
 *  - Chapter 3 (semantic <header>, <nav>)
 *  - Chapter 12 ($_SESSION for login state)
 *
 * Before including, pages set $page_title and optionally $active_nav.
 */
require_once __DIR__ . '/helpers.php';

$page_title = $page_title ?? SITE_NAME;
$active_nav = $active_nav ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= sanitize($page_title) ?> &middot; <?= sanitize(SITE_NAME) ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/main.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/gallery.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/forms.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/responsive.css">
</head>
<body>
<header class="site-header">
    <div class="container">
        <a href="<?= BASE_URL ?>/index.php" class="site-logo">Aurelia <span>Art</span></a>
        <nav class="main-nav">
            <ul>
                <li><a href="<?= BASE_URL ?>/index.php"    class="<?= $active_nav === 'home' ? 'active' : '' ?>">Home</a></li>
                <li><a href="<?= BASE_URL ?>/gallery.php"  class="<?= $active_nav === 'gallery' ? 'active' : '' ?>">Gallery</a></li>
                <li><a href="<?= BASE_URL ?>/artists.php"  class="<?= $active_nav === 'artists' ? 'active' : '' ?>">Artists</a></li>
                <li><a href="<?= BASE_URL ?>/about.php"    class="<?= $active_nav === 'about' ? 'active' : '' ?>">About</a></li>
                <?php if (is_logged_in()): ?>
                    <li><a href="<?= BASE_URL ?>/favorites.php" class="<?= $active_nav === 'favorites' ? 'active' : '' ?>">Favorites</a></li>
                    <li><a href="<?= BASE_URL ?>/logout.php" class="btn btn-sm"><?= sanitize($_SESSION['username']) ?> &middot; Logout</a></li>
                <?php else: ?>
                    <li><a href="<?= BASE_URL ?>/login.php" class="btn btn-sm">Login</a></li>
                    <li><a href="<?= BASE_URL ?>/register.php" class="btn btn-sm btn-primary">Register</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>
<main class="main-content">
