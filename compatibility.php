<?php
/*
 * Compatibility Checking Functions
 * Procedural PHP implementation for component validation
 */

// Main compatibility checking function
function checkAllCompatibility($connection, $selected_components) {
    $issues = array();
    
    if (!is_array($selected_components) || empty($selected_components)) {
        return $issues; // No components selected, no issues
    }
    
    // Check CPU-Motherboard compatibility (only if both are selected)
    if (isset($selected_components['CPU']) && $selected_components['CPU'] > 0 && 
        isset($selected_components['Motherboard']) && $selected_components['Motherboard'] > 0) {
        $cpu_mb_issue = checkCPUMotherboardCompatibility($connection, $selected_components['CPU'], $selected_components['Motherboard']);
        if ($cpu_mb_issue) {
            $issues[] = $cpu_mb_issue;
        }
    }
    
    // Check Motherboard-RAM compatibility (only if both are selected)
    if (isset($selected_components['Motherboard']) && $selected_components['Motherboard'] > 0 && 
        isset($selected_components['RAM']) && $selected_components['RAM'] > 0) {
        $mb_ram_issue = checkMotherboardRAMCompatibility($connection, $selected_components['Motherboard'], $selected_components['RAM']);
        if ($mb_ram_issue) {
            $issues[] = $mb_ram_issue;
        }
    }
    
    return $issues;
}

// Check CPU and Motherboard compatibility
function checkCPUMotherboardCompatibility($connection, $cpu_id, $motherboard_id) {
    $cpu = getComponentById($connection, $cpu_id);
    $motherboard = getComponentById($connection, $motherboard_id);
    
    if (!$cpu || !$motherboard) {
        return "Missing component data for compatibility check";
    }
    
    // Check socket compatibility
    if ($cpu['socket_type'] != $motherboard['socket_type']) {
        $cpu_name = $cpu['brand'] . ' ' . $cpu['name'];
        $mb_name = $motherboard['brand'] . ' ' . $motherboard['name'];
        return "CPU ($cpu_name) uses {$cpu['socket_type']} socket but Motherboard ($mb_name) uses {$motherboard['socket_type']} socket";
    }
    
    return null; // No issues
}

// Check Motherboard and RAM compatibility
function checkMotherboardRAMCompatibility($connection, $motherboard_id, $ram_id) {
    $motherboard = getComponentById($connection, $motherboard_id);
    $ram = getComponentById($connection, $ram_id);
    
    if (!$motherboard || !$ram) {
        return "Missing component data for compatibility check";
    }
    
    // Check RAM type compatibility
    if ($motherboard['ram_type'] != $ram['ram_type']) {
        $mb_name = $motherboard['brand'] . ' ' . $motherboard['name'];
        $ram_name = $ram['brand'] . ' ' . $ram['name'];
        return "Motherboard ($mb_name) supports {$motherboard['ram_type']} but RAM ($ram_name) is {$ram['ram_type']}";
    }
    
    return null; // No issues
}

// Function to check power requirements
function checkPowerRequirements($connection, $selected_components) {
    $total_power = 0;
    
    if (!is_array($selected_components)) {
        return $total_power;
    }
    
    foreach ($selected_components as $component_id) {
        if ($component_id && $component_id > 0) {
            $component = getComponentById($connection, $component_id);
            if ($component && isset($component['power_requirements'])) {
                $total_power += $component['power_requirements'];
            }
        }
    }
    
    // Add overhead for other components
    $total_power += 100;
    
    return $total_power;
}
?>