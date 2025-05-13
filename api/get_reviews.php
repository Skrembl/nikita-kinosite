<?php
header('Content-Type: application/json');

$filmId = $_GET['film_id'] ?? '';
if (!$filmId) {
    echo json_encode(['reviews' => []]);
    exit;
}

$path = __DIR__ . '/../reviews.json';
$data = json_decode(file_get_contents($path), true) ?: [];
$reviews = $data[$filmId] ?? [];

echo json_encode(['reviews' => $reviews]);
