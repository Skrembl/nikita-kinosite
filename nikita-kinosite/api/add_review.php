<?php
header('Content-Type: application/json');

$name   = trim($_POST['name'] ?? '');
$text   = trim($_POST['text'] ?? '');
$filmId = $_POST['film_id'] ?? '';

if (!$name || !$text || !$filmId) {
    echo json_encode(['success' => false, 'error' => 'Неверные данные']);
    exit;
}

$path = __DIR__ . '/../reviews.json';
$fp = fopen($path, 'c+');
if (flock($fp, LOCK_EX)) {
    $raw = stream_get_contents($fp);
    $data = $raw ? json_decode($raw, true) : [];
    $date = date('Y-m-d H:i:s');
    $review = ['name' => htmlspecialchars($name), 'text' => htmlspecialchars($text), 'date' => $date];
    $data[$filmId][] = $review;
    ftruncate($fp, 0);
    rewind($fp);
    fwrite($fp, json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    fflush($fp);
    flock($fp, LOCK_UN);
    fclose($fp);
    echo json_encode(['success' => true]);
} else {
    fclose($fp);
    echo json_encode(['success' => false, 'error' => 'Не удалось заблокировать файл']);
}
