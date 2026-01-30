<?php
require_once 'config.php';

// Check if user is logged in
if (!isLoggedIn()) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Get and validate input
$path = isset($_POST['path']) ? $_POST['path'] : '';

if (empty($path)) {
    echo json_encode(['success' => false, 'message' => 'No file specified']);
    exit;
}

// Construct full file path
$filePath = '..' . (strpos($path, '/') === 0 ? '' : '/') . $path;

// Security check: ensure the file is within the images directory
$uploadDir = realpath('../images/');
$fileRealPath = realpath($filePath);

if ($fileRealPath === false || strpos($fileRealPath, $uploadDir) !== 0) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Invalid file path']);
    exit;
}

try {
    // Check if file exists and is a file (not a directory)
    if (!file_exists($filePath) || !is_file($filePath)) {
        throw new Exception('The file does not exist or is not a regular file.');
    }
    
    // Delete the file
    if (@unlink($filePath)) {
        echo json_encode([
            'success' => true,
            'message' => 'File deleted successfully'
        ]);
    } else {
        throw new Exception('Failed to delete the file. Check file permissions.');
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error deleting file: ' . $e->getMessage()
    ]);
}
?>
