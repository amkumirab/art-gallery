<?php
/**
 * api/artworks.php - JSON endpoint for gallery search/filter
 * Chapter 10 (jQuery AJAX consumes this), Chapter 11 (PHP output)
 */
require_once __DIR__ . '/../includes/helpers.php';

$artworkModel = new Artwork();

// Read filters from $_GET (Chapter 12)
$filters = [
    'search'      => $_GET['search'] ?? '',
    'category_id' => $_GET['category_id'] ?? '',
    'artist_id'   => $_GET['artist_id'] ?? '',
    'sort'        => $_GET['sort'] ?? 'newest',
];

$artworks = $artworkModel->getAll($filters);

// Build JSON-friendly array
$result = [];
foreach ($artworks as $art) {
    $result[] = [
        'id'             => (int)$art['id'],
        'title'          => $art['title'],
        'artist_first'   => $art['artist_first'] ?? '',
        'artist_last'    => $art['artist_last'] ?? '',
        'category_name'  => $art['category_name'] ?? '',
        'year'           => (int)$art['year'],
        'price'          => $art['price'],
        'image_filename' => $art['image_filename'],
        'is_featured'    => (bool)$art['is_featured'],
    ];
}

header('Content-Type: application/json');
echo json_encode($result);
