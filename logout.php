<?php
/**
 * logout.php - Destroy the session and return to home.
 * Chapter 12 ($_SESSION management).
 */
require_once __DIR__ . '/includes/helpers.php';

$_SESSION = [];
session_unset();
session_destroy();

redirect(BASE_URL . '/index.php');
