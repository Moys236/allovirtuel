<?php
session_start();
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

// Define base directory
$baseDir = realpath(dirname(__DIR__));

// Handle file operations
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'load') {
    // Load file content
    if (!isset($_GET['file'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'No file specified']);
        exit();
    }
    
    $filePath = $baseDir . '/' . $_GET['file'];
    
    // Security check: prevent directory traversal
    if (strpos(realpath($filePath), $baseDir) !== 0) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Access denied']);
        exit();
    }
    
    if (!file_exists($filePath) || !is_file($filePath)) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'File not found']);
        exit();
    }
    
    // Check if file is readable
    if (!is_readable($filePath)) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'File is not readable']);
        exit();
    }
    
    // Get file content
    $content = file_get_contents($filePath);
    if ($content === false) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to read file']);
        exit();
    }
    
    // Return file content
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'content' => $content,
        'size' => filesize($filePath),
        'modified' => filemtime($filePath)
    ]);
    
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Save file content
    $input = [];
    parse_str(file_get_contents('php://input'), $input);
    
    if (!isset($input['action']) || $input['action'] !== 'save' || !isset($input['file'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid request']);
        exit();
    }
    
    $filePath = $baseDir . '/' . $input['file'];
    $content = $input['content'] ?? '';
    
    // Security check: prevent directory traversal
    if (strpos(realpath(dirname($filePath)), $baseDir) !== 0) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Access denied']);
        exit();
    }
    
    // Check if file exists and is writable
    if (file_exists($filePath) && !is_writable($filePath)) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'File is not writable']);
        exit();
    }
    
    // Check if directory is writable
    if (!is_writable(dirname($filePath))) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Directory is not writable']);
        exit();
    }
    
    // Create directory if it doesn't exist
    if (!file_exists(dirname($filePath))) {
        if (!mkdir(dirname($filePath), 0755, true)) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to create directory']);
            exit();
        }
    }
    
    // Save file
    $result = file_put_contents($filePath, $content);
    
    if ($result === false) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to save file']);
        exit();
    }
    
    // Return success
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'message' => 'File saved successfully',
        'size' => $result,
        'modified' => time()
    ]);
    
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit();
}
?>
