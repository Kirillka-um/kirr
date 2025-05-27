<?php
header('Content-Type: application/json');

$botToken = '7684855295:AAHHoTCf4nOCLBR8GVoiZNwO6ZOlXrFpJQg';
$chatId = '7217266343'; // Узнать можно через @userinfobot

$text = $_POST['text'] ?? '';
$photo = $_FILES['photo'] ?? null;

if (!$text || !$photo) {
    echo json_encode(['success' => false, 'error' => 'Не все данные переданы']);
    exit;
}

// Загрузка фото на сервер Telegram
$url = "https://api.telegram.org/bot{$botToken}/sendPhoto";
$postData = [
    'chat_id' => $chatId,
    'caption' => "Новая машина: {$text}",
    'photo' => new CURLFile($photo['tmp_name'], $photo['type'], $photo['name'])
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

$result = json_decode($response, true);

if ($result['ok']) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Ошибка Telegram API: ' . $result['description']]);
}
?>
