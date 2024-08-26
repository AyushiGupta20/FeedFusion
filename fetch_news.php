<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

$api_key = 'ac9aa92477c84e598c74e5e7dedc39d9'; // Replace with your actual API key
$topics = isset($_GET['topics']) ? $_GET['topics'] : '';

if (empty($topics)) {
    echo json_encode(["error" => "No topics provided"]);
    exit;
}

$topicsArray = array_map('trim', explode(',', $topics));
$allArticles = [];

foreach ($topicsArray as $topic) {
    $url = "https://newsapi.org/v2/everything?q={$topic}&apiKey={$api_key}";
    $response = @file_get_contents($url);

    if ($response === FALSE) {
        error_log("Failed to fetch news for topic: $topic");
        continue;
    }

    // Log the raw response for debugging
    error_log("Raw API Response: " . $response);

    $data = json_decode($response, true);

    // Log the decoded JSON for debugging
    error_log("Decoded Data: " . print_r($data, true));

    if (isset($data['code']) && $data['code'] === 'rateLimited') {
        echo json_encode(["error" => "Rate limit exceeded. Try again later."]);
        exit;
    }

    if (isset($data['articles'])) {
        $allArticles = array_merge($allArticles, $data['articles']);
    }
}

header('Content-Type: application/json');
echo json_encode(['articles' => $allArticles]);
?>
