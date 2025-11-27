<?php
/*
 * My Builds Page
 * Displays user's saved PC configurations with delete option
 */

require_once 'config.php';
$db_connection = connectDatabase();

// Handle build deletion
if (isset($_GET['delete_build'])) {
    $build_id = intval($_GET['delete_build']);
    
    // Verify the build exists
    $check_query = "SELECT * FROM user_builds WHERE id = $build_id";
    $check_result = mysqli_query($db_connection, $check_query);
    
    if (mysqli_num_rows($check_result) > 0) {
        $delete_query = "DELETE FROM user_builds WHERE id = $build_id";
        if (mysqli_query($db_connection, $delete_query)) {
            $_SESSION['message'] = "Build deleted successfully!";
        } else {
            $_SESSION['message'] = "Error deleting build: " . mysqli_error($db_connection);
        }
    } else {
        $_SESSION['message'] = "Build not found!";
    }
    
    // Redirect to refresh the page
    header("Location: my_builds.php");
    exit();
}

// Get all saved builds
$query = "SELECT ub.*, 
                 cpu.name as cpu_name, cpu.brand as cpu_brand, cpu.price as cpu_price,
                 mb.name as mb_name, mb.brand as mb_brand, mb.price as mb_price,
                 ram.name as ram_name, ram.brand as ram_brand, ram.price as ram_price,
                 gpu.name as gpu_name, gpu.brand as gpu_brand, gpu.price as gpu_price
          FROM user_builds ub
          LEFT JOIN components cpu ON ub.cpu_id = cpu.id
          LEFT JOIN components mb ON ub.motherboard_id = mb.id
          LEFT JOIN components ram ON ub.ram_id = ram.id
          LEFT JOIN components gpu ON ub.gpu_id = gpu.id
          ORDER BY ub.created_at DESC";
$builds_result = mysqli_query($db_connection, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Saved Builds - EVETech PC Builder</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .build-card {
            transition: transform 0.2s;
            border-left: 4px solid #0d6efd;
        }
        .build-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .component-item {
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .component-item:last-child {
            border-bottom: none;
        }
        .price-tag {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
        }
        .delete-btn {
            position: absolute;
            top: 10px;
            right: 10px;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <header class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="text-primary">My Saved PC Builds</h1>
                <p class="lead">Review and manage your computer configurations</p>
            </div>
            <a href="index.php" class="btn btn-primary">← Back to Builder</a>
        </header>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <?php if (mysqli_num_rows($builds_result) > 0): ?>
                <?php while ($build = mysqli_fetch_assoc($builds_result)): ?>
                    <div class="col-lg-6 col-xl-4 mb-4">
                        <div class="card build-card h-100 position-relative">
                            <!-- Delete Button -->
                            <button type="button" class="btn btn-danger btn-sm delete-btn" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteModal"
                                    data-build-id="<?php echo $build['id']; ?>"
                                    data-build-name="<?php echo htmlspecialchars($build['build_name']); ?>">
                                🗑️
                            </button>
                            
                            <div class="card-header bg-white">
                                <h4 class="text-primary mb-1"><?php echo htmlspecialchars($build['build_name']); ?></h4>
                                <small class="text-muted">Created: <?php echo date('j M Y, g:i A', strtotime($build['created_at'])); ?></small>
                            </div>
                            <div class="card-body">
                                <div class="build-components">
                                    <div class="component-item">
                                        <strong>💻 Processor:</strong><br>
                                        <?php if ($build['cpu_name']): ?>
                                            <?php echo $build['cpu_brand'] . ' ' . $build['cpu_name']; ?><br>
                                            <small class="text-success"><?php echo CURRENCY_SYMBOL . number_format($build['cpu_price'], 2); ?></small>
                                        <?php else: ?>
                                            <span class="text-muted">Not selected</span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="component-item">
                                        <strong>🔌 Motherboard:</strong><br>
                                        <?php if ($build['mb_name']): ?>
                                            <?php echo $build['mb_brand'] . ' ' . $build['mb_name']; ?><br>
                                            <small class="text-success"><?php echo CURRENCY_SYMBOL . number_format($build['mb_price'], 2); ?></small>
                                        <?php else: ?>
                                            <span class="text-muted">Not selected</span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="component-item">
                                        <strong>💾 Memory:</strong><br>
                                        <?php if ($build['ram_name']): ?>
                                            <?php echo $build['ram_brand'] . ' ' . $build['ram_name']; ?><br>
                                            <small class="text-success"><?php echo CURRENCY_SYMBOL . number_format($build['ram_price'], 2); ?></small>
                                        <?php else: ?>
                                            <span class="text-muted">Not selected</span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="component-item">
                                        <strong>🎮 Graphics Card:</strong><br>
                                        <?php if ($build['gpu_name']): ?>
                                            <?php echo $build['gpu_brand'] . ' ' . $build['gpu_name']; ?><br>
                                            <small class="text-success"><?php echo CURRENCY_SYMBOL . number_format($build['gpu_price'], 2); ?></small>
                                        <?php else: ?>
                                            <span class="text-muted">Not selected</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-white">
                                <div class="price-tag">
                                    <h4 class="mb-0">Total: <?php echo CURRENCY_SYMBOL . number_format($build['total_price'], 2); ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info text-center py-5">
                        <h4>🚧 No saved builds yet!</h4>
                        <p class="mb-3">You haven't saved any PC configurations yet.</p>
                        <p class="mb-3">Go back to the builder and create your perfect PC!</p>
                        <a href="index.php" class="btn btn-primary btn-lg">Start Building Your PC</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <?php if (mysqli_num_rows($builds_result) > 0): ?>
            <div class="row mt-4">
                <div class="col-12 text-center">
                    <p class="text-muted">
                        <small>Showing <?php echo mysqli_num_rows($builds_result); ?> saved build(s)</small>
                    </p>
                </div>
            </div>
        <?php endif; ?>
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
                    <p>Are you sure you want to delete build "<span id="buildNameToDelete"></span>"?</p>
                    <p class="text-danger"><strong>This action cannot be undone!</strong></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <a href="#" id="confirmDeleteBtn" class="btn btn-danger">Yes, Delete Build</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Delete confirmation modal functionality
        document.addEventListener('DOMContentLoaded', function() {
            var deleteModal = document.getElementById('deleteModal');
            var buildNameSpan = document.getElementById('buildNameToDelete');
            var confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
            
            deleteModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                var buildId = button.getAttribute('data-build-id');
                var buildName = button.getAttribute('data-build-name');
                
                buildNameSpan.textContent = buildName;
                confirmDeleteBtn.href = 'my_builds.php?delete_build=' + buildId;
            });
        });
    </script>
</body>
</html>

<?php mysqli_close($db_connection); ?>