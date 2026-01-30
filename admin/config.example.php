<?php
session_start();

/**
 * Configuration Database
 * 
 * Copy this file to config.php and fill in your database credentials
 * NEVER commit the config.php file to version control!
 */

// Database configuration for localhost (development)
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'allovirtuel');

// Database configuration for production (InfinityFree, Heroku, etc.)
// Uncomment and update with your production credentials
// define('DB_HOST', 'your_production_host.com');
// define('DB_USER', 'your_production_user');
// define('DB_PASS', 'your_strong_password_here');
// define('DB_NAME', 'your_production_database');

/**
 * Create database connection
 * @return PDO|null
 */
function getDbConnection()
{
    try {
        $pdo = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
            DB_USER,
            DB_PASS,
            array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            )
        );
        return $pdo;
    } catch (PDOException $e) {
        die("Database Connection failed: " . $e->getMessage());
    }
}

/**
 * Sanitize user input to prevent XSS
 * @param string $data
 * @return string
 */
function sanitize($data)
{
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

/**
 * Check if user is logged in
 * @return bool
 */
function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

/**
 * Redirect to another page
 * @param string $url
 */
function redirect($url)
{
    header("Location: $url");
    exit();
}
?>