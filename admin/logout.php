<?php
/**
 * admin/logout.php - End admin session
 */
require_once __DIR__ . '/../includes/helpers.php';
$_SESSION = [];
session_unset();
session_destroy();
redirect(BASE_URL . '/admin/login.php');
