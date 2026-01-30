<?php
require_once 'config.php';

// Check if user is logged in
if (!isLoggedIn()) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$uploadDir = '../images/';
$images = [];

try {
    if (is_dir($uploadDir)) {
        $files = scandir($uploadDir);
        
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') continue;
            
            $filePath = $uploadDir . $file;
            if (is_file($filePath) && in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                $images[] = [
                    'name' => $file,
                    'path' => 'images/' . $file,
                    'size' => filesize($filePath),
                    'modified' => filemtime($filePath)
                ];
            }
        }
        
        // Sort by modification time (newest first)
        usort($images, function($a, $b) {
            return $b['modified'] - $a['modified'];
        });
    }
    
    echo json_encode([
        'success' => true,
        'images' => $images
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error reading images: ' . $e->getMessage()
    ]);
}
?>
