<?php
require 'db_config.php';

$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : '';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT preferred_topics FROM user_preferences WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$preferences = $result->fetch_assoc();

header('Content-Type: application/json');
echo json_encode($preferences);

$stmt->close();
$conn->close();

