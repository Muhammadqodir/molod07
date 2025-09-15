#!/usr/bin/env php
<?php

// Простой тестовый скрипт для проверки API
// Использование: php test_api.php

$baseUrl = 'http://localhost:8000/api/partner';

function makeRequest($url, $data = null, $token = null) {
    $ch = curl_init();

    $headers = [
        'Content-Type: application/json',
        'Accept: application/json'
    ];

    if ($token) {
        $headers[] = 'Authorization: Bearer ' . $token;
    }

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    if ($data) {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return [
        'status' => $httpCode,
        'data' => json_decode($response, true)
    ];
}

echo "=== Тестирование API партнёра ===\n\n";

// 1. Тест авторизации
echo "1. Тестируем авторизацию...\n";
$loginData = [
    'email' => 'partner@example.com',
    'password' => 'password'
];

$response = makeRequest($baseUrl . '/login', $loginData);
echo "Статус: " . $response['status'] . "\n";
echo "Ответ: " . json_encode($response['data'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n\n";

if ($response['status'] === 200 && isset($response['data']['data']['token'])) {
    $token = $response['data']['data']['token'];
    echo "Токен получен: " . substr($token, 0, 20) . "...\n\n";

    // 2. Тест получения информации о пользователе
    echo "2. Тестируем получение информации о пользователе (ID: 1)...\n";
    $response = makeRequest($baseUrl . '/user/1', null, $token);
    echo "Статус: " . $response['status'] . "\n";
    echo "Ответ: " . json_encode($response['data'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n\n";

    // 3. Тест списания баллов
    echo "3. Тестируем списание баллов...\n";
    $deductData = [
        'user_id' => 1,
        'points' => 10,
        'description' => 'Тестовая покупка'
    ];

    $response = makeRequest($baseUrl . '/deduct-points', $deductData, $token);
    echo "Статус: " . $response['status'] . "\n";
    echo "Ответ: " . json_encode($response['data'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n\n";

    // 4. Тест истории транзакций
    echo "4. Тестируем получение истории транзакций...\n";
    $response = makeRequest($baseUrl . '/transactions', null, $token);
    echo "Статус: " . $response['status'] . "\n";
    echo "Ответ: " . json_encode($response['data'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n\n";

} else {
    echo "Ошибка авторизации! Проверьте данные партнёра в базе данных.\n";
}

echo "=== Тестирование завершено ===\n";
