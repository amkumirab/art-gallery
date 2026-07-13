<?php
/**
 * api/review.php - Submit a review via AJAX (POST)
 * Chapter 10 (jQuery AJAX), Chapter 12 ($_POST, $_SESSION)
 */
require_once __DIR__ . '/../includes/helpers.php';
require_login();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'POST required']);
    exit;
}

$artworkId = (int)($_POST['artwork_id'] ?? 0);
$rating    = (int)($_POST['rating'] ?? 0);
$comment   = trim($_POST['comment'] ?? '');

if ($artworkId <= 0 || $rating < 1 || $rating > 5) {
    echo json_encode(['error' => 'Invalid data']);
    exit;
}

$reviewModel = new Review();
$reviewModel->add($_SESSION['user_id'], $artworkId, $rating, $comment);

echo json_encode(['success' => true, 'message' => 'Review added']);
