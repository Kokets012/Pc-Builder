<?php
/*
 * PC Builder Configuration File
 * Ready for both localhost and InfinityFree
 */

// Detect if we're on localhost or production
$is_localhost = ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == '127.0.0.1');

if ($is_localhost) {
    // Localhost configuration
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'pc_builder');
} else {
    // InfinityFree configuration - YOU WILL UPDATE THESE
    define('DB_HOST', 'sql100.infinityfree.com');
    define('DB_USER', 'if0_40466273');
    define('DB_PASS', 'wOk7fIQGUc7yiY');
    define('DB_NAME', 'if0_40466273_pc_builder');
}

// Currency settings for South Africa
define('CURRENCY', 'ZAR');
define('CURRENCY_SYMBOL', 'R');

// Start session for user builds
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Error reporting (disable for production)
if ($is_localhost) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Database connection function
function connectDatabase() {
    $connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if (mysqli_connect_errno()) {
        die("Database connection failed: " . mysqli_connect_error());
    }
    
    return $connection;
}

// Global variables for components
$component_categories = array(
    'CPU' => 'Processor',
    'Motherboard' => 'Motherboard', 
    'RAM' => 'Memory',
    'GPU' => 'Graphics Card'
);

// Include all function files
require_once 'components.php';
require_once 'compatibility.php';
?>