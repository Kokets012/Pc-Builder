<?php
/*
 * PC Builder Compatibility Checker - Main File
 * Fixed version with proper form handling
 */

// Include configuration and functions
require_once 'config.php';
require_once 'components.php';
require_once 'compatibility.php';

// Database connection
$db_connection = connectDatabase();

// Initialize selected components array
if (!isset($_SESSION['selected_components'])) {
    $_SESSION['selected_components'] = array(
        'CPU' => 0,
        'Motherboard' => 0,
        'RAM' => 0,
        'GPU' => 0
    );
}

// Handle component selection from form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'select_component' && isset($_POST['category']) && isset($_POST['component_id'])) {
            $category = mysqli_real_escape_string($db_connection, $_POST['category']);
            $component_id = intval($_POST['component_id']);
            
            // Only update if it's a valid category
            if (array_key_exists($category, $_SESSION['selected_components'])) {
                $_SESSION['selected_components'][$category] = $component_id;
            }
        }
        elseif ($_POST['action'] === 'clear_build') {
            $_SESSION['selected_components'] = array(
                'CPU' => 0,
                'Motherboard' => 0,
                'RAM' => 0,
                'GPU' => 0
            );
        }
    }
}

// Always check compatibility after any POST action
$compatibility_issues = checkAllCompatibility($db_connection, $_SESSION['selected_components']);

// Calculate total price
$total_price = calculateTotalPrice($db_connection, $_SESSION['selected_components']);

// Get all components for dropdowns
$all_cpus = getAllComponentsByCategory($db_connection, 'CPU');
$all_motherboards = getAllComponentsByCategory($db_connection, 'Motherboard');
$all_ram = getAllComponentsByCategory($db_connection, 'RAM');
$all_gpus = getAllComponentsByCategory($db_connection, 'GPU');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EVEPC PC Builder - Compatibility Checker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <header class="text-center mb-5">
            <h1 class="display-4 text-primary">EVEPC Builder</h1>
            <p class="lead">Build your perfect PC with guaranteed compatibility</p>
        </header>

        <div class="row">
            <!-- Component Selection Section -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">Select Your Components</h3>
                        <form method="post" class="m-0">
                            <input type="hidden" name="action" value="clear_build">
                            <button type="submit" class="btn btn-warning btn-sm">Clear Build</button>
                        </form>
                    </div>
                    <div class="card-body">
                        <!-- CPU Selection -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Processor (CPU)</label>
                            <form method="post" class="component-form">
                                <input type="hidden" name="action" value="select_component">
                                <input type="hidden" name="category" value="CPU">
                                <select class="form-select component-select" name="component_id" onchange="this.form.submit()">
                                    <option value="0">Select a CPU</option>
                                    <?php
                                    while ($cpu = mysqli_fetch_assoc($all_cpus)) {
                                        $selected = ($_SESSION['selected_components']['CPU'] == $cpu['id']) ? 'selected' : '';
                                        echo "<option value='{$cpu['id']}' $selected>
                                                {$cpu['brand']} {$cpu['name']} - R{$cpu['price']} ({$cpu['socket_type']})
                                              </option>";
                                    }
                                    mysqli_data_seek($all_cpus, 0);
                                    ?>
                                </select>
                            </form>
                        </div>

                        <!-- Motherboard Selection -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Motherboard</label>
                            <form method="post" class="component-form">
                                <input type="hidden" name="action" value="select_component">
                                <input type="hidden" name="category" value="Motherboard">
                                <select class="form-select component-select" name="component_id" onchange="this.form.submit()">
                                    <option value="0">Select a Motherboard</option>
                                    <?php
                                    while ($mb = mysqli_fetch_assoc($all_motherboards)) {
                                        $selected = ($_SESSION['selected_components']['Motherboard'] == $mb['id']) ? 'selected' : '';
                                        echo "<option value='{$mb['id']}' $selected>
                                                {$mb['brand']} {$mb['name']} - R{$mb['price']} ({$mb['socket_type']}/{$mb['ram_type']})
                                              </option>";
                                    }
                                    mysqli_data_seek($all_motherboards, 0);
                                    ?>
                                </select>
                            </form>
                        </div>

                        <!-- RAM Selection -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Memory (RAM)</label>
                            <form method="post" class="component-form">
                                <input type="hidden" name="action" value="select_component">
                                <input type="hidden" name="category" value="RAM">
                                <select class="form-select component-select" name="component_id" onchange="this.form.submit()">
                                    <option value="0">Select RAM</option>
                                    <?php
                                    while ($ram = mysqli_fetch_assoc($all_ram)) {
                                        $selected = ($_SESSION['selected_components']['RAM'] == $ram['id']) ? 'selected' : '';
                                        echo "<option value='{$ram['id']}' $selected>
                                                {$ram['brand']} {$ram['name']} - R{$ram['price']} ({$ram['ram_type']})
                                              </option>";
                                    }
                                    mysqli_data_seek($all_ram, 0);
                                    ?>
                                </select>
                            </form>
                        </div>

                        <!-- GPU Selection -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Graphics Card (GPU) - Optional</label>
                            <form method="post" class="component-form">
                                <input type="hidden" name="action" value="select_component">
                                <input type="hidden" name="category" value="GPU">
                                <select class="form-select component-select" name="component_id" onchange="this.form.submit()">
                                    <option value="0">Select a Graphics Card (Optional)</option>
                                    <?php
                                    while ($gpu = mysqli_fetch_assoc($all_gpus)) {
                                        $selected = ($_SESSION['selected_components']['GPU'] == $gpu['id']) ? 'selected' : '';
                                        echo "<option value='{$gpu['id']}' $selected>
                                                {$gpu['brand']} {$gpu['name']} - R{$gpu['price']}
                                              </option>";
                                    }
                                    mysqli_data_seek($all_gpus, 0);
                                    ?>
                                </select>
                            </form>
                        </div>

                        <!-- Compatibility Status -->
                        <div class="compatibility-section mt-4">
                            <h4>Compatibility Check</h4>
                            <?php
                            if (!empty($compatibility_issues)) {
                                echo '<div class="alert alert-danger">';
                                echo '<strong>❌ Compatibility Issues Found:</strong>';
                                echo '<ul class="mb-0">';
                                foreach ($compatibility_issues as $issue) {
                                    echo "<li>$issue</li>";
                                }
                                echo '</ul>';
                                echo '</div>';
                            } else {
                                $selected_count = 0;
                                foreach ($_SESSION['selected_components'] as $component_id) {
                                    if ($component_id > 0) $selected_count++;
                                }
                                
                                if ($selected_count > 0) {
                                    echo '<div class="alert alert-success">✅ All selected components are compatible!</div>';
                                } else {
                                    echo '<div class="alert alert-info">Select components to check compatibility</div>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary Section -->
            <div class="col-md-4">
                <div class="card sticky-top">
                    <div class="card-header bg-success text-white">
                        <h3>Build Summary</h3>
                    </div>
                    <div class="card-body">
                        <div id="selectedComponents">
                            <h5>Selected Components:</h5>
                            <div id="componentsList">
                                <?php
                                $has_components = false;
                                
                                foreach ($_SESSION['selected_components'] as $category => $component_id) {
                                    if ($component_id > 0) {
                                        $component = getComponentById($db_connection, $component_id);
                                        if ($component) {
                                            $has_components = true;
                                            echo "<div class='selected-component mb-2 p-2 border rounded'>";
                                            echo "<strong>{$component_categories[$category]}:</strong><br>";
                                            echo "{$component['brand']} {$component['name']}<br>";
                                            echo "<small class='text-success'>R{$component['price']}</small>";
                                            echo "</div>";
                                        }
                                    }
                                }
                                
                                if (!$has_components) {
                                    echo "<p class='text-muted'>No components selected yet</p>";
                                }
                                ?>
                            </div>
                        </div>
                        <hr>
                        <div class="total-price">
                            <h4>Total: R<span id="totalPrice"><?php echo number_format($total_price, 2); ?></span></h4>
                        </div>
                        
                        <!-- Save Build Button -->
                        <?php if ($total_price > 0): ?>
                            <div class="mt-3">
                                <form method="post" action="save_build.php">
                                    <div class="mb-2">
                                        <input type="text" class="form-control" name="build_name" placeholder="Name your build" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">💾 Save This Build</button>
                                </form>
                                <a href="my_builds.php" class="btn btn-outline-secondary w-100 mt-2">📋 View My Builds</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Close database connection
mysqli_close($db_connection);
?>