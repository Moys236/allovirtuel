<?php
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Define allowed directories
$baseDir = realpath(dirname(__DIR__));
$allowedDirs = [
    'root' => $baseDir,
    'images' => $baseDir . '/images',
    'admin' => $baseDir . '/admin',
    'js' => $baseDir . '/js',
    'css' => $baseDir . '/css'
];

// Get current directory
$currentDir = isset($_GET['dir']) ? realpath($baseDir . '/' . $_GET['dir']) : $baseDir;

// Security check: prevent directory traversal
if (strpos($currentDir, $baseDir) !== 0) {
    $currentDir = $baseDir;
}

// Handle file operations
$message = '';
$messageType = '';

// Process file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $targetDir = $currentDir . '/' . basename($_FILES['file']['name']);
    
    if (move_uploaded_file($_FILES['file']['tmp_name'], $targetDir)) {
        $message = 'File uploaded successfully';
        $messageType = 'success';
    } else {
        $message = 'Error uploading file';
        $messageType = 'danger';
    }
}

// Process file/folder creation
if (isset($_POST['create'])) {
    $name = trim($_POST['name']);
    $type = $_POST['type'];
    $target = $currentDir . '/' . $name;
    
    if (!empty($name)) {
        if ($type === 'file') {
            if (file_put_contents($target, '') !== false) {
                $message = 'File created successfully';
                $messageType = 'success';
            } else {
                $message = 'Error creating file';
                $messageType = 'danger';
            }
        } else {
            if (mkdir($target, 0755, true)) {
                $message = 'Directory created successfully';
                $messageType = 'success';
            } else {
                $message = 'Error creating directory';
                $messageType = 'danger';
            }
        }
    }
}

// Process file/folder deletion
if (isset($_GET['delete'])) {
    $target = $currentDir . '/' . $_GET['delete'];
    
    if (is_dir($target)) {
        if ($this->deleteDirectory($target)) {
            $message = 'Directory deleted successfully';
            $messageType = 'success';
        } else {
            $message = 'Error deleting directory';
            $messageType = 'danger';
        }
    } else {
        if (unlink($target)) {
            $message = 'File deleted successfully';
            $messageType = 'success';
        } else {
            $message = 'Error deleting file';
            $messageType = 'danger';
        }
    }
}

// Helper function to recursively delete a directory
function deleteDirectory($dir) {
    if (!file_exists($dir)) {
        return true;
    }
    
    if (!is_dir($dir)) {
        return unlink($dir);
    }
    
    foreach (scandir($dir) as $item) {
        if ($item === '.' || $item === '..') {
            continue;
        }
        
        if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
            return false;
        }
    }
    
    return rmdir($dir);
}

// Get directory contents
$files = [];
$folders = [];

if (is_dir($currentDir)) {
    $items = scandir($currentDir);
    
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') continue;
        
        $path = $currentDir . '/' . $item;
        $relativePath = substr($path, strlen($baseDir) + 1);
        
        $itemInfo = [
            'name' => $item,
            'path' => $path,
            'relative_path' => $relativePath,
            'is_dir' => is_dir($path),
            'size' => filesize($path),
            'modified' => filemtime($path),
            'permissions' => substr(sprintf('%o', fileperms($path)), -4)
        ];
        
        if ($itemInfo['is_dir']) {
            $folders[] = $itemInfo;
        } else {
            $files[] = $itemInfo;
        }
    }
}

// Sort folders and files
usort($folders, function($a, $b) {
    return strcmp($a['name'], $b['name']);
});

usort($files, function($a, $b) {
    return strcmp($a['name'], $b['name']);
});

// Get relative path for breadcrumbs
$relativePath = trim(substr($currentDir, strlen($baseDir)), '/');
$breadcrumbs = $relativePath ? explode('/', $relativePath) : [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Manager - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .file-icon { width: 24px; text-align: center; }
        .file-size { font-size: 0.8rem; color: #6c757d; }
        .file-modified { font-size: 0.8rem; color: #6c757d; }
        .breadcrumb { background-color: #f8f9fa; padding: 0.5rem 1rem; border-radius: 0.25rem; }
        .file-actions { visibility: hidden; }
        tr:hover .file-actions { visibility: visible; }
        .editor-container { display: none; }
        .editor-container.active { display: block; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row
        <?php include 'includes/header.php'; ?>
        
        <div class="container mt-4">
            <h2>File Manager</h2>
            
            <?php if ($message): ?>
                <div class="alert alert-<?= $messageType ?> alert-dismissible fade show" role="alert">
                    <?= $message ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            
            <!-- Breadcrumb Navigation -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="?" class="text-decoration-none">
                            <i class="fas fa-home"></i> Root
                        </a>
                    </li>
                    <?php 
                    $currentPath = '';
                    foreach ($breadcrumbs as $i => $crumb): 
                        $currentPath .= ($i > 0 ? '/' : '') . $crumb;
                    ?>
                        <li class="breadcrumb-item">
                            <a href="?dir=<?= urlencode($currentPath) ?>" class="text-decoration-none">
                                <?= htmlspecialchars($crumb) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ol>
            </nav>
            
            <!-- Quick Navigation -->
            <div class="mb-3">
                <div class="btn-group" role="group">
                    <?php foreach ($allowedDirs as $name => $path): 
                        $isActive = $currentDir === $path ? 'btn-primary' : 'btn-outline-secondary';
                    ?>
                        <a href="?dir=<?= urlencode(substr($path, strlen($baseDir) + 1)) ?>" 
                           class="btn btn-sm <?= $isActive ?>">
                            <i class="fas fa-folder"></i> <?= ucfirst($name) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="mb-3">
                <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#uploadModal">
                    <i class="fas fa-upload"></i> Upload File
                </button>
                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="fas fa-plus"></i> New File/Folder
                </button>
            </div>
            
            <!-- File List -->
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Size</th>
                                    <th>Modified</th>
                                    <th>Permissions</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Parent Directory -->
                                <?php if ($currentDir !== $baseDir): ?>
                                    <tr>
                                        <td colspan="5">
                                            <a href="?dir=<?= urlencode(dirname($relativePath)) ?>" class="text-decoration-none">
                                                <i class="fas fa-level-up-alt"></i> Parent Directory
                                            </a>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                                
                                <!-- Folders -->
                                <?php foreach ($folders as $folder): ?>
                                    <tr>
                                        <td>
                                            <i class="fas fa-folder text-warning"></i>
                                            <a href="?dir=<?= urlencode($folder['relative_path']) ?>" class="text-decoration-none">
                                                <?= htmlspecialchars($folder['name']) ?>
                                            </a>
                                        </td>
                                        <td>-</td>
                                        <td><?= date('Y-m-d H:i:s', $folder['modified']) ?></td>
                                        <td><?= $folder['permissions'] ?></td>
                                        <td class="file-actions">
                                            <a href="?dir=<?= urlencode($folder['relative_path']) ?>" class="btn btn-sm btn-outline-primary" title="Open">
                                                <i class="fas fa-folder-open"></i>
                                            </a>
                                            <button class="btn btn-sm btn-outline-danger delete-file" 
                                                    data-name="<?= htmlspecialchars($folder['name']) ?>"
                                                    data-type="directory"
                                                    title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                
                                <!-- Files -->
                                <?php foreach ($files as $file): 
                                    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                                    $icon = getFileIcon($ext);
                                    $size = formatFileSize($file['size']);
                                ?>
                                    <tr>
                                        <td>
                                            <i class="<?= $icon ?> me-1"></i>
                                            <a href="#" class="text-decoration-none edit-file" 
                                               data-path="<?= htmlspecialchars($file['relative_path']) ?>">
                                                <?= htmlspecialchars($file['name']) ?>
                                            </a>
                                        </td>
                                        <td class="file-size"><?= $size ?></td>
                                        <td class="file-modified"><?= date('Y-m-d H:i:s', $file['modified']) ?></td>
                                        <td><?= $file['permissions'] ?></td>
                                        <td class="file-actions">
                                            <a href="../<?= htmlspecialchars($file['relative_path']) ?>" 
                                               target="_blank" 
                                               class="btn btn-sm btn-outline-primary" 
                                               title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button class="btn btn-sm btn-outline-secondary edit-file" 
                                                    data-path="<?= htmlspecialchars($file['relative_path']) ?>"
                                                    title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <a href="../<?= htmlspecialchars($file['relative_path']) ?>" 
                                               download 
                                               class="btn btn-sm btn-outline-success" 
                                               title="Download">
                                                <i class="fas fa-download"></i>
                                            </a>
                                            <button class="btn btn-sm btn-outline-danger delete-file" 
                                                    data-name="<?= htmlspecialchars($file['name']) ?>"
                                                    data-type="file"
                                                    title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                
                                <?php if (empty($folders) && empty($files)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <i class="fas fa-folder-open fa-3x text-muted mb-2"></i>
                                            <p class="mb-0">This directory is empty</p>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- File Editor -->
            <div class="card mt-4 editor-container" id="fileEditor">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0" id="editorTitle">Edit File</h5>
                    <button type="button" class="btn-close" id="closeEditor"></button>
                </div>
                <div class="card-body p-0">
                    <form id="saveFileForm">
                        <input type="hidden" id="editFilePath" name="path" value="">
                        <div class="form-group mb-0">
                            <textarea class="form-control" id="fileContent" rows="20" style="font-family: monospace;"></textarea>
                        </div>
                        <div class="card-footer text-end">
                            <button type="button" class="btn btn-secondary" id="cancelEdit">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Upload Modal -->
        <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="modal-header">
                            <h5 class="modal-title" id="uploadModalLabel">Upload File</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="file" class="form-label">Select file to upload:</label>
                                <input type="file" class="form-control" id="file" name="file" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Upload</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Create Modal -->
        <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="" method="post">
                        <div class="modal-header">
                            <h5 class="modal-title" id="createModalLabel">Create New</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="type" id="typeFile" value="file" checked>
                                    <label class="form-check-label" for="typeFile">File</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="type" id="typeFolder" value="folder">
                                    <label class="form-check-label" for="typeFolder">Folder</label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                                <div class="form-text">For files, include the extension (e.g., example.txt)</div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" name="create" class="btn btn-primary">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Delete Confirmation Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete <strong id="deleteItemName"></strong>?</p>
                        <p class="text-danger">This action cannot be undone.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <a href="#" id="confirmDelete" class="btn btn-danger">Delete</a>
                    </div>
                </div>
            </div>
        </div>
        
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize tooltips
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
                
                // Handle file editing
                document.querySelectorAll('.edit-file').forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        const filePath = this.getAttribute('data-path');
                        loadFileForEditing(filePath);
                    });
                });
                
                // Handle file deletion
                document.querySelectorAll('.delete-file').forEach(button => {
                    button.addEventListener('click', function() {
                        const fileName = this.getAttribute('data-name');
                        const fileType = this.getAttribute('data-type');
                        const deleteUrl = `?delete=${encodeURIComponent(fileName)}&dir=<?= urlencode($relativePath) ?>`;
                        
                        document.getElementById('deleteItemName').textContent = fileName;
                        document.getElementById('confirmDelete').setAttribute('href', deleteUrl);
                        
                        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
                        deleteModal.show();
                    });
                });
                
                // Close editor
                document.getElementById('closeEditor').addEventListener('click', closeEditor);
                document.getElementById('cancelEdit').addEventListener('click', closeEditor);
                
                // Save file form
                document.getElementById('saveFileForm').addEventListener('submit', function(e) {
                    e.preventDefault();
                    saveFile();
                });
                
                // Function to load file for editing
                function loadFileForEditing(filePath) {
                    fetch('file_editor.php?action=load&file=' + encodeURIComponent(filePath))
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                document.getElementById('editorTitle').textContent = 'Edit: ' + filePath;
                                document.getElementById('editFilePath').value = filePath;
                                document.getElementById('fileContent').value = data.content;
                                document.getElementById('fileEditor').classList.add('active');
                                
                                // Scroll to editor
                                document.getElementById('fileEditor').scrollIntoView({ behavior: 'smooth' });
                            } else {
                                alert('Error loading file: ' + data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Error loading file');
                        });
                }
                
                // Function to save file
                function saveFile() {
                    const filePath = document.getElementById('editFilePath').value;
                    const content = document.getElementById('fileContent').value;
                    
                    fetch('file_editor.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `action=save&file=${encodeURIComponent(filePath)}&content=${encodeURIComponent(content)}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showAlert('File saved successfully', 'success');
                        } else {
                            throw new Error(data.message || 'Error saving file');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showAlert('Error saving file: ' + error.message, 'danger');
                    });
                }
                
                // Function to close editor
                function closeEditor() {
                    document.getElementById('fileEditor').classList.remove('active');
                }
                
                // Function to show alert
                function showAlert(message, type) {
                    const alertDiv = document.createElement('div');
                    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
                    alertDiv.role = 'alert';
                    alertDiv.innerHTML = `
                        ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    `;
                    
                    const container = document.querySelector('.container');
                    container.insertBefore(alertDiv, container.firstChild);
                    
                    // Auto-remove alert after 5 seconds
                    setTimeout(() => {
                        alertDiv.remove();
                    }, 5000);
                }
            });
        </script>
    </div>
</body>
</html>

<?php
// Helper function to get file icon based on extension
function getFileIcon($ext) {
    $icons = [
        'php' => 'fab fa-php text-primary',
        'html' => 'fab fa-html5 text-orange',
        'htm' => 'fab fa-html5 text-orange',
        'css' => 'fab fa-css3-alt text-blue',
        'js' => 'fab fa-js text-warning',
        'json' => 'fas fa-code text-warning',
        'jpg' => 'fas fa-image text-success',
        'jpeg' => 'fas fa-image text-success',
        'png' => 'fas fa-image text-primary',
        'gif' => 'fas fa-image text-info',
        'svg' => 'fas fa-image text-warning',
        'pdf' => 'fas fa-file-pdf text-danger',
        'doc' => 'fas fa-file-word text-primary',
        'docx' => 'fas fa-file-word text-primary',
        'xls' => 'fas fa-file-excel text-success',
        'xlsx' => 'fas fa-file-excel text-success',
        'ppt' => 'fas fa-file-powerpoint text-orange',
        'pptx' => 'fas fa-file-powerpoint text-orange',
        'zip' => 'fas fa-file-archive text-muted',
        'rar' => 'fas fa-file-archive text-muted',
        'txt' => 'fas fa-file-alt text-secondary',
        'md' => 'fas fa-file-alt text-secondary',
        'sql' => 'fas fa-database text-primary',
        'xml' => 'fas fa-code text-info'
    ];
    
    $ext = strtolower($ext);
    return $icons[$ext] ?? 'fas fa-file text-secondary';
}

// Helper function to format file size
function formatFileSize($bytes) {
    if ($bytes === 0) return '0 B';
    
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $i = floor(log($bytes, 1024));
    $size = round($bytes / pow(1024, $i), 2);
    
    return $size . ' ' . $units[$i];
}
?>
