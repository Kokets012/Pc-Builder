<?php
/*
 * Components Management Functions
 * Procedural PHP functions for component handling
 */

// Function to get all components by category
function getAllComponentsByCategory($connection, $category) {
    $category = mysqli_real_escape_string($connection, $category);
    $query = "SELECT * FROM components WHERE category = '$category' ORDER BY brand, name";
    $result = mysqli_query($connection, $query);
    
    if (!$result) {
        die("Database query failed: " . mysqli_error($connection));
    }
    
    return $result;
}

// Function to get component by ID
function getComponentById($connection, $component_id) {
    $component_id = intval($component_id);
    $query = "SELECT * FROM components WHERE id = $component_id";
    $result = mysqli_query($connection, $query);
    
    if (!$result || mysqli_num_rows($result) == 0) {
        return null;
    }
    
    return mysqli_fetch_assoc($result);
}

// Function to calculate total price of selected components
function calculateTotalPrice($connection, $selected_components) {
    $total = 0;
    
    foreach ($selected_components as $component_id) {
        $component = getComponentById($connection, $component_id);
        if ($component) {
            $total += $component['price'];
        }
    }
    
    return $total;
}

// Function to get component specifications for display
function getComponentSpecsDisplay($component) {
    $specs = array();
    
    if (!empty($component['specs'])) {
        $specs[] = $component['specs'];
    }
    if (!empty($component['socket_type'])) {
        $specs[] = "Socket: " . $component['socket_type'];
    }
    if (!empty($component['ram_type'])) {
        $specs[] = "RAM Type: " . $component['ram_type'];
    }
    
    return implode(' | ', $specs);
}
?>