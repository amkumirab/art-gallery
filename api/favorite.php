<?php
/**
 * api/favorite.php - AJAX endpoint to add/remove favorite
 */
// اضافه کردن مسیرهای اصلی دیتابیس برای اتصال به پورت ۳۳۰۷
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/Database.php';
require_once __DIR__ . '/../includes/Favorite.php';
require_once __DIR__ . '/../includes/helpers.php';

// بقیه کدهای فایل دست‌نخورده باقی بماند...
require_login(); // Must be logged in

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'POST required']);
    exit;
}

$artworkId = (int)($_POST['artwork_id'] ?? 0);
if ($artworkId <= 0) {
    echo json_encode(['error' => 'Invalid artwork ID']);
    exit;
}

$favModel = new Favorite();
$result = $favModel->toggle($_SESSION['user_id'], $artworkId);

echo json_encode([
    'status' => $result, // 'added' or 'removed'
    'artwork_id' => $artworkId,
]);
