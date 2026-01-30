<?php
require_once 'config.php';

// Check if user is logged in
if (!isLoggedIn()) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Get the filename from the query string
$filename = isset($_GET['filename']) ? $_GET['filename'] : '';

if (empty($filename)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'No filename provided']);
    exit;
}

// Security: Only allow filenames with alphanumeric, dots, hyphens, and underscores
if (!preg_match('/^[a-zA-Z0-9._-]+$/', $filename)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid filename']);
    exit;
}

// Define the images directory
$imagesDir = '../images/';
$filePath = $imagesDir . $filename;

// Check if the file exists and is a regular file
$exists = file_exists($filePath) && is_file($filePath);

// Return the result
header('Content-Type: application/json');
echo json_encode([
    'success' => true,
    'exists' => $exists,
    'filename' => $filename
]);
?>
