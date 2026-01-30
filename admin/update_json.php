<?php
// update_json.php

// Set headers to allow CORS and specify JSON content type
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Get the raw POST data
$jsonData = file_get_contents('php://input');
$data = json_decode($jsonData, true);

// Check if JSON is valid
if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid JSON data: ' . json_last_error_msg()
    ]);
    exit;
}

// Get language from the request or default to 'en'
$language = isset($data['_language']) ? $data['_language'] : 'en';
$validLanguages = ['en', 'fr']; // Add more languages as needed

// Validate language
if (!in_array($language, $validLanguages)) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid language specified'
    ]);
    exit;
}

// Remove the _language field from the data before saving
unset($data['_language']);

// Path to the JSON file based on language
$jsonFile = "../allovirtuelContent_{$language}.json";

// Function to safely update JSON file
function updateJsonFile($file, $newData) {
    // Read existing data
    $existingData = [];
    if (file_exists($file)) {
        $existingData = json_decode(file_get_contents($file), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $existingData = [];
        }
    }
    
    // Merge new data with existing (deep merge)
    $mergedData = array_replace_recursive($existingData, $newData);
    
    // Create backup before saving
    $backupFile = $file . '.bak';
    if (file_exists($file)) {
        if (!copy($file, $backupFile)) {
            error_log("Failed to create backup of $file");
        }
    }
    
    // Save the updated data back to the file
    $result = file_put_contents(
        $file, 
        json_encode($newData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
    );
    
    if ($result === false) {
        error_log("Failed to write to $file");
        return false;
    }
    
    // If save was successful, remove the backup
    if (file_exists($backupFile)) {
        unlink($backupFile);
    }
    
    return true;
}

// Update the JSON file
if (updateJsonFile($jsonFile, $data)) {
    http_response_code(200);
    echo json_encode([
        'status' => 'success',
        'message' => 'Data updated successfully',
        'data' => $data
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to update data'
    ]);
}
?>