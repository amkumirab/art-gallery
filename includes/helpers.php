<?php
/**
 * helpers.php - Reusable utility functions
 *
 * Uses Chapter 12 (superglobals: $_SESSION) and Chapter 11 (functions).
 */

require_once __DIR__ . '/Database.php';

/**
 * Autoload all model classes (Chapter 13: OOP).
 * This spl_autoload_register call means we never need manual require_once
 * for each class — PHP loads it automatically when "new ClassName()" is used.
 */
spl_autoload_register(function ($className) {
    $path = __DIR__ . '/' . $className . '.php';
    if (file_exists($path)) {
        require_once $path;
    }
});

/**
 * Sanitize a value for safe HTML output (prevents XSS).
 * @param mixed $value
 * @return string
 */
function sanitize($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

/**
 * Redirect to a URL and stop execution.
 * @param string $url
 */
function redirect($url)
{
    header('Location: ' . $url);
    exit;
}

/**
 * Is a visitor logged in? (Chapter 12 - $_SESSION)
 * @return bool
 */
function is_logged_in()
{
    return isset($_SESSION['user_id']);
}

/**
 * Is the logged-in user an admin?
 * @return bool
 */
function is_admin()
{
    return is_logged_in() && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

/**
 * Require a login to view the current page; otherwise redirect to login.
 */
function require_login()
{
    if (!is_logged_in()) {
        redirect(BASE_URL . '/login.php');
    }
}

/**
 * Require admin privileges to view the current page.
 */
function require_admin()
{
    if (!is_admin()) {
        redirect(BASE_URL . '/admin/login.php');
    }
}

/**
 * Flash message helpers (store a one-time message in $_SESSION).
 * @param string $key
 * @param string|null $message
 * @return string|null
 */
function set_flash($key, $message)
{
    $_SESSION['flash'][$key] = $message;
}

function get_flash($key)
{
    if (isset($_SESSION['flash'][$key])) {
        $msg = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $msg;
    }
    return null;
}

/**
 * Format a price as currency.
 * @param float|null $price
 * @return string
 */
function format_price($price)
{
    if ($price === null) {
        return '—';
    }
    return '$' . number_format((float)$price, 2);
}
