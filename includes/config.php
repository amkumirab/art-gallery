<?php
/**
 * config.php - Database credentials and global configuration
 *
 * Edit the four DB_* constants below to match your local MySQL setup.
 * Chapter 11 (PHP Introduction): variables, constants, configuration.
 */

// ---- Database credentials (CHANGE THESE TO MATCH YOUR SETUP) ----
define('DB_HOST', 'localhost');
define('DB_PORT', '3307');       // <-- Your MySQL port (was default 3306)
define('DB_NAME', 'art_gallery');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// ---- Site configuration ----
define('SITE_NAME', 'Aurelia Art Gallery');
define('BASE_URL',  '/art-gallery');

// ---- File upload configuration ----
define('UPLOAD_DIR', __DIR__ . '/../assets/uploads/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5 MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/webp']);

// ---- Error reporting (turn off display_errors in production) ----
error_reporting(E_ALL);
ini_set('display_errors', '1');

// ---- Start the session on every request (Chapter 12 - superglobals) ----
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
