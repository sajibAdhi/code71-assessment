<?php

include_once('config.php');

// Set the content type to JSON
header('Content-Type: application/json');

// Allow only POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Only POST method is allowed']);
    exit;
}

// Read the input JSON data
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Basic input validation
if (!isset($data['product_id'], $data['user_id'], $data['review_text'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required fields: product_id, user_id, review_text']);
    exit;
}

$product_id = $data['product_id'];
$user_id = $data['user_id'];
$review_text = $data['review_text'];

if (!is_numeric($product_id) || !is_numeric($user_id) || empty($review_text)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid input: product_id and user_id should be numeric, review_text should not be empty']);
    exit;
}

// Connect to MySQL database and store the review
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed: ' . $conn->connect_error]);
    exit;
}

$sql = "INSERT INTO reviews (product_id, user_id, review_text) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iis", $product_id, $user_id, $review_text);

if ($stmt->execute()) {
    http_response_code(201);
    echo json_encode(['message' => 'Review submitted successfully']);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to submit review']);
}

$stmt->close();
$conn->close();
