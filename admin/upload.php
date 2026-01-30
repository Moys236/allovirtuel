<?php
require_once 'config.php';

// Check if user is logged in
if (!isLoggedIn()) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Create images directory if it doesn't exist
$uploadDir = '../images/';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Allowed file types
$allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
$maxFileSize = 5 * 1024 * 1024; // 5MB

// Function to get upload error message
function getUploadError($code) {
    switch ($code) {
        case UPLOAD_ERR_INI_SIZE:
            return 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
        case UPLOAD_ERR_FORM_SIZE:
            return 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
        case UPLOAD_ERR_PARTIAL:
            return 'The uploaded file was only partially uploaded';
        case UPLOAD_ERR_NO_FILE:
            return 'No file was uploaded';
        case UPLOAD_ERR_NO_TMP_DIR:
            return 'Missing a temporary folder';
        case UPLOAD_ERR_CANT_WRITE:
            return 'Failed to write file to disk';
        case UPLOAD_ERR_EXTENSION:
            return 'A PHP extension stopped the file upload';
        default:
            return 'Unknown upload error';
    }
}

$response = [
    'success' => false,
    'message' => 'No files were uploaded.',
    'files' => []
];

// If there are no files or not a POST request, return error
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_FILES['images']) || !is_array($_FILES['images']['name'])) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'No files were uploaded.'
    ]);
    exit;
}

$uploadedFiles = [];
$errors = [];
$existingFiles = [];

// First, check all files for duplicates and validate
$totalFiles = count($_FILES['images']['name']);
for ($i = 0; $i < $totalFiles; $i++) {
    $fileName = basename($_FILES['images']['name'][$i]);
    
    // Skip if no file was uploaded for this field
    if ($_FILES['images']['error'][$i] === UPLOAD_ERR_NO_FILE) {
        continue;
    }
    
    // Handle any upload errors
    if ($_FILES['images']['error'][$i] !== UPLOAD_ERR_OK) {
        $errors[] = "$fileName: " . getUploadError($_FILES['images']['error'][$i]);
        continue;
    }
    
    $fileTmp = $_FILES['images']['tmp_name'][$i];
    $fileType = $_FILES['images']['type'][$i];
    $fileSize = $_FILES['images']['size'][$i];
    $targetFile = $uploadDir . $fileName;
    
    // Check if file already exists
    if (file_exists($targetFile)) {
        $existingFiles[] = $fileName;
        continue;
    }
    
    // Validate file type
    if (!in_array($fileType, $allowedTypes)) {
        $errors[] = "$fileName: Invalid file type. Only JPG, PNG, and GIF are allowed.";
        continue;
    }
        
    // Validate file size
    if ($fileSize > $maxFileSize) {
        $errors[] = "$fileName: File is too large. Maximum size is 5MB.";
        continue;
    }
    
    // Sanitize and prepare filename
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $baseName = pathinfo($fileName, PATHINFO_FILENAME);
    $baseName = preg_replace('/[^a-zA-Z0-9\-_]/', '_', $baseName); // Sanitize filename
    $baseName = substr($baseName, 0, 50); // Limit filename length
    
    // Use the original filename (we already checked for existence)
    $newFileName = $baseName . '.' . $fileExt;
    $destination = $uploadDir . $newFileName;
    
    // Move the uploaded file to its final destination
    if (move_uploaded_file($fileTmp, $destination)) {
        $uploadedFiles[] = [
            'original' => $fileName,
            'saved' => $newFileName,
            'path' => 'images/' . $newFileName, // Return path relative to site root
            'size' => $fileSize,
            'type' => $fileType,
            'wasRenamed' => false
        ];
    } else {
        $errors[] = "Failed to move uploaded file: $fileName";
    }
}

// If there are duplicates, return error before any uploads
if (!empty($existingFiles)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Some files already exist. Please rename or delete them first: ' . 
                    implode(', ', array_slice($existingFiles, 0, 5)) . 
                    (count($existingFiles) > 5 ? ' and ' . (count($existingFiles) - 5) . ' more' : ''),
        'duplicates' => $existingFiles
    ]);
    exit;
}

// If we have files to upload, process them
if (!empty($uploadedFiles)) {
    $response = [
        'success' => true,
        'message' => count($uploadedFiles) . ' file(s) uploaded successfully',
        'files' => $uploadedFiles
    ];
} else if (!empty($errors)) {
    $response = [
        'success' => false,
        'message' => 'No files were uploaded. ' . implode(' ', $errors),
        'errors' => $errors
    ];
} else {
    $response['message'] = 'No files were uploaded or invalid request method.';
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);

?>
