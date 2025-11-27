<?php
/*
 * Save Build Functionality
 * Handles saving user configurations to database
 */

require_once 'config.php'; // This now includes components.php and compatibility.php

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['build_name'])) {
    $db_connection = connectDatabase();
    
    // Sanitize inputs
    $build_name = mysqli_real_escape_string($db_connection, $_POST['build_name']);
    $cpu_id = isset($_SESSION['selected_components']['CPU']) ? intval($_SESSION['selected_components']['CPU']) : 0;
    $motherboard_id = isset($_SESSION['selected_components']['Motherboard']) ? intval($_SESSION['selected_components']['Motherboard']) : 0;
    $ram_id = isset($_SESSION['selected_components']['RAM']) ? intval($_SESSION['selected_components']['RAM']) : 0;
    $gpu_id = isset($_SESSION['selected_components']['GPU']) ? intval($_SESSION['selected_components']['GPU']) : 0;
    
    // Calculate total price using the function from components.php
    $total_price = calculateTotalPrice($db_connection, $_SESSION['selected_components']);
    
    // Insert into database
    $query = "INSERT INTO user_builds (build_name, cpu_id, motherboard_id, ram_id, gpu_id, total_price) 
              VALUES ('$build_name', $cpu_id, $motherboard_id, $ram_id, $gpu_id, $total_price)";
    
    if (mysqli_query($db_connection, $query)) {
        $_SESSION['message'] = "Build '$build_name' saved successfully!";
    } else {
        $_SESSION['message'] = "Error saving build: " . mysqli_error($db_connection);
    }
    
    mysqli_close($db_connection);
    
    // Redirect back to main page
    header("Location: index.php");
    exit();
} else {
    header("Location: index.php");
    exit();
}
?>