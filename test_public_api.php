<?php

/**
 * Simple API test script
 * Usage: php test_public_api.php
 */

function testApiEndpoint($url, $description) {
    echo "Testing: $description\n";
    echo "URL: $url\n";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if (curl_error($ch)) {
        echo "cURL Error: " . curl_error($ch) . "\n";
        curl_close($ch);
        return false;
    }

    curl_close($ch);

    echo "HTTP Code: $httpCode\n";

    if ($httpCode == 200) {
        $data = json_decode($response, true);
        if ($data && isset($data['status']) && $data['status'] === 'success') {
            echo "✅ Success\n";
            if (isset($data['data']) && is_array($data['data'])) {
                echo "Items count: " . count($data['data']) . "\n";
            }
            if (isset($data['pagination'])) {
                echo "Pagination: Page {$data['pagination']['current_page']} of {$data['pagination']['last_page']}, Total: {$data['pagination']['total']}\n";
            }
        } else {
            echo "❌ Invalid response format\n";
            echo "Response: " . substr($response, 0, 200) . "...\n";
        }
    } else {
        echo "❌ HTTP Error\n";
        echo "Response: " . substr($response, 0, 200) . "...\n";
    }

    echo "\n" . str_repeat("-", 50) . "\n\n";
    return $httpCode == 200;
}

// Base URL - change this to your domain
$baseUrl = "http://localhost:8000/api/public";

echo "🧪 Testing Public API Endpoints\n";
echo "Base URL: $baseUrl\n";
echo str_repeat("=", 50) . "\n\n";

// Test Events
testApiEndpoint("$baseUrl/events", "Get Events List");
testApiEndpoint("$baseUrl/events?per_page=5", "Get Events List (5 per page)");
testApiEndpoint("$baseUrl/events?search=молодежь", "Search Events");
testApiEndpoint("$baseUrl/events/1", "Get Single Event");

// Test News
testApiEndpoint("$baseUrl/news", "Get News List");
testApiEndpoint("$baseUrl/news?per_page=3", "Get News List (3 per page)");
testApiEndpoint("$baseUrl/news?category=спорт", "Filter News by Category");
testApiEndpoint("$baseUrl/news/1", "Get Single News");

// Test Grants
testApiEndpoint("$baseUrl/grants", "Get Grants List");
testApiEndpoint("$baseUrl/grants?per_page=10", "Get Grants List (10 per page)");
testApiEndpoint("$baseUrl/grants?search=стартап", "Search Grants");
testApiEndpoint("$baseUrl/grants/1", "Get Single Grant");

// Test Vacancies
testApiEndpoint("$baseUrl/vacancies", "Get Vacancies List");
testApiEndpoint("$baseUrl/vacancies?per_page=7", "Get Vacancies List (7 per page)");
testApiEndpoint("$baseUrl/vacancies?category=IT", "Filter Vacancies by Category");
testApiEndpoint("$baseUrl/vacancies?salary_from=50000", "Filter Vacancies by Salary");
testApiEndpoint("$baseUrl/vacancies/1", "Get Single Vacancy");

echo "🏁 Testing completed!\n";
