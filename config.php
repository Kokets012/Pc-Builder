<?php
/*
 * PC Builder Configuration File - Localhost Version
 * South African Rands Edition
 */

// Database configuration for localhost
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'pc_builder');

// Currency settings for South Africa
define('CURRENCY', 'R');
define('CURRENCY_SYMBOL', 'R');

// Start session for user builds
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Error reporting (enable for development)
error_reporting(E_ALL);
ini_set('display_errors', 1);

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