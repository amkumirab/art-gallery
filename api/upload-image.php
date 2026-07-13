<?php
/**
 * api/upload-image.php - Handle async image upload (FormData)
 * Chapter 10 (jQuery AJAX file upload via FormData + XMLHttpRequest)
 * Chapter 12 ($_FILES superglobal)
 */
require_once __DIR__ . '/../includes/helpers.php';
require_admin(); // Only admin can upload

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'POST required']);
    exit;
}

if (empty($_FILES['image'])) {
    echo json_encode(['error' => 'No file uploaded']);
    exit;
}

$file = $_FILES['image'];

if ($file['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['error' => 'Upload error code: ' . $file['error']]);
    exit;
}

// Validate type
$fileType = mime_content_type($file['tmp_name']);
if (!in_array($fileType, ALLOWED_IMAGE_TYPES)) {
    echo json_encode(['error' => 'Only JPG, PNG, and WebP images are allowed.']);
    exit;
}

// Validate size
if ($file['size'] > MAX_FILE_SIZE) {
    echo json_encode(['error' => 'Maximum file size is 5 MB.']);
    exit;
}

// Generate unique filename and move
$ext  = pathinfo($file['name'], PATHINFO_EXTENSION);
$filename = uniqid('art_') . '.' . $ext;
$destination = UPLOAD_DIR . $filename;

if (move_uploaded_file($file['tmp_name'], $destination)) {
    echo json_encode([
        'success' => true,
        'filename' => $filename,
        'url' => BASE_URL . '/assets/uploads/' . $filename,
    ]);
} else {
    echo json_encode(['error' => 'Failed to save file.']);
}
