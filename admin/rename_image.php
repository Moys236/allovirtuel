<?php
require_once 'config.php';

// Check if user is logged in
if (!isLoggedIn()) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Get and validate input
$oldPath = isset($_POST['oldPath']) ? $_POST['oldPath'] : '';
$newName = isset($_POST['newName']) ? trim($_POST['newName']) : '';

if (empty($oldPath) || empty($newName)) {
    echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
    exit;
}

// Validate filename
if (!preg_match('/^[a-zA-Z0-9\-_]+$/', $newName)) {
    echo json_encode(['success' => false, 'message' => 'Invalid filename. Only letters, numbers, hyphens, and underscores are allowed.']);
    exit;
}

// Get file extension from old path
$extension = pathinfo($oldPath, PATHINFO_EXTENSION);
$newFileName = $newName . '.' . $extension;
$uploadDir = '../images/';
$oldFilePath = '..' . (strpos($oldPath, '/') === 0 ? '' : '/') . $oldPath;
$newFilePath = $uploadDir . $newFileName;

try {
    // Check if old file exists
    if (!file_exists($oldFilePath)) {
        throw new Exception('The file does not exist.');
    }
    
    // Check if new filename already exists
    if (file_exists($newFilePath) && realpath($oldFilePath) !== realpath($newFilePath)) {
        throw new Exception('A file with this name already exists.');
    }
    
    // Rename the file
    if (rename($oldFilePath, $newFilePath)) {
        echo json_encode([
            'success' => true,
            'message' => 'File renamed successfully',
            'newPath' => 'images/' . $newFileName
        ]);
    } else {
        throw new Exception('Failed to rename the file.');
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error renaming file: ' . $e->getMessage()
    ]);
}
?>
