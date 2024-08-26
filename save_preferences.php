<?php
require 'db_config.php';

$user_id = isset($_POST['user_id']) ? $_POST['user_id'] : '';
$preferred_topics = isset($_POST['preferred_topics']) ? $_POST['preferred_topics'] : '';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "INSERT INTO user_preferences (user_id, preferred_topics) VALUES (?, ?) 
        ON DUPLICATE KEY UPDATE preferred_topics = VALUES(preferred_topics)";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ss', $user_id, $preferred_topics);
if ($stmt->execute()) {
    echo 'Preferences saved successfully';
} else {
    echo 'Error saving preferences: ' . $stmt->error;
}

$stmt->close();
$conn->close();

