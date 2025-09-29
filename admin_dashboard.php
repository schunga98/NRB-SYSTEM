<?php
// admin_update_status.php
// Example of how to integrate notification system with admin status updates

require_once 'database.php';
require_once 'notification_handler.php';

// Start session for admin authentication
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

$database = new Database();
$db = $database->getConnection();
$notificationHandler = new NotificationHandler();

// Handle status update requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $application_id = $_POST['application_id'];
    $new_status = $_POST['new_status'];
    $admin_notes = $_POST['admin_notes'] ?? '';
    $collection_center = $_POST['collection_center'] ?? 'NRB Main Office';
    
    try {
        // Get current application details
        $query = "SELECT user_id, status, reference_number FROM applications WHERE id = :application_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':application_id', $application_id);
        $stmt->execute();
        $application = $stmt->fetch();
        
        if ($application) {
            $old_status = $application['status'];
            $user_id = $application['user_id'];
            $reference_number = $application['reference_number'];
            
            // Update application status
            $update_query = "UPDATE applications 
                           SET status = :new_status, 
                               admin_notes = :admin_notes,
                               collection_center = :collection_center,
                               processed_at = NOW() 
                           WHERE id = :application_id";
            
            $update_stmt = $db->prepare($update_query);
            $update_stmt->bindParam(':new_status', $new_status);
            $update_stmt->bindParam(':admin_notes', $admin_notes);
            $update_stmt->bindParam(':collection_center', $collection_center);
            $update_stmt->bindParam(':application_id', $application_id);
            
            if ($update_stmt->execute()) {
                // The database trigger will automatically create the notification
                // But if you want manual control, you can use:
                // $notificationHandler->createStatusChangeNotification($user_id, $reference_number, $old_status, $new_status);
                
                $success_message = "Application status updated successfully and notification sent to user.";
                
                // Log admin action
                $log_query = "INSERT INTO admin_activity_log (admin_id, action, application_id, details, created_at) 
                             VALUES (:admin_id, 'status_update', :application_id, :details, NOW())";
                $log_stmt = $db->prepare($log_query);
                $log_stmt->bindParam(':admin_id', $_SESSION['admin_id']);
                $log_stmt->bindParam(':application_id', $application_id);
                $log_stmt->bindValue(':details', "Status changed from {$old_status} to {$new_status}");
                $log_stmt->execute();
                
            } else {
                $error_message = "Failed to update application status.";
            }
        } else {
            $error_message = "Application not found.";
        }
    } catch (PDOException $e) {
        $error_message = "Database error: " . $e->getMessage();
    }
}

// Get all pending applications for admin review
try {
    $query = "SELECT a.*, u.full_name, u.email, u.phone 
              FROM applications a 
              JOIN users u ON a.user_id = u.id 
              WHERE a.status IN ('submitted', 'under_review') 
              ORDER BY a.submitted_at ASC";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $applications = $stmt->fetchAll();
} catch (PDOException $e) {
    $error_message = "Failed to fetch applications: " . $e->getMessage();
    $applications = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Application Management</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #2c5aa0 0%, #1e4080 100%);
            color: white;
            padding: 20px;
            margin: -30px -30px 30px -30px;
            border-radius: 10px 10px 0 0;
        }
        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .alert-success {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }
        .alert-error {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }
        .application-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 20px;
            padding: 20px;
            background: #fafafa;
        }
        .application-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-submitted {
            background: #fff3cd;
            color: #856404;
        }
        .status-under-review {
            background: #d1ecf1;
            color: #0c5460;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-control {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
        }
        .btn-primary {
            background: #2c5aa0;
            color: white;
        }
        .btn-success {
            background: #28a745;
            color: white;
        }
        .btn-warning {
            background: #ffc107;
            color: #212529;
        }
        .btn-danger {
            background: #dc3545;
            color: white;
        }
        .btn:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }
        .user-info {
            background: #e3f2fd;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .status-update-form {
            background: white;
            padding: 20px;
            border-radius: 5px;
            border: 2px solid #2c5aa0;
            margin-top: 15px;
        }
        .form-row {
            display: flex;
            gap: 15px;
            align-items: end;
        }
        .form-row .form-group {
            flex: 1;
        }
        @media (max-width: 768px) {
            .form-row {
                flex-direction: column;
            }
            .application-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-tasks"></i> Application Management</h1>
            <p>Review and update citizen ID renewal applications</p>
        </div>

        <?php if (isset($success_message)): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($success_message); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <div class="alert alert-error">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <?php if (empty($applications)): ?>
            <div class="alert alert-success">
                <h3>All Caught Up! 🎉</h3>
                <p>No pending applications to review at this time.</p>
            </div>
        <?php else: ?>
            <?php foreach ($applications as $app): ?>
                <div class="application-card">
                    <div class="application-header">
                        <div>
                            <h3>Application #<?php echo htmlspecialchars($app['reference_number']); ?></h3>
                            <p><strong>Submitted:</strong> <?php echo date('F j, Y \a\t g:i A', strtotime($app['submitted_at'])); ?></p>
                        </div>
                        <span class="status-badge status-<?php echo $app['status']; ?>">
                            <?php echo ucwords(str_replace('_', ' ', $app['status'])); ?>
                        </span>
                    </div>

                    <div class="user-info">
                        <h4>Citizen Information</h4>
                        <p><strong>Name:</strong> <?php echo htmlspecialchars($app['full_name']); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($app['email']); ?></p>
                        <p><strong>Phone:</strong> <?php echo htmlspecialchars($app['phone']); ?></p>
                        <p><strong>Application Type:</strong> <?php echo ucwords($app['application_type']); ?></p>
                        <p><strong>Reason:</strong> <?php echo htmlspecialchars($app['reason']); ?></p>
                    </div>

                    <div class="status-update-form">
                        <h4>Update Application Status</h4>
                        <form method="POST" action="">
                            <input type="hidden" name="application_id" value="<?php echo $app['id']; ?>">
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="new_status_<?php echo $app['id']; ?>">New Status:</label>
                                    <select name="new_status" id="new_status_<?php echo $app['id']; ?>" class="form-control" required>
                                        <option value="">Select Status</option>
                                        <option value="under_review">Under Review</option>
                                        <option value="verified">Verified</option>
                                        <option value="processing">Processing</option>
                                        <option value="ready">Ready for Collection</option>
                                        <option value="rejected">Rejected</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="collection_center_<?php echo $app['id']; ?>">Collection Center:</label>
                                    <select name="collection_center" id="collection_center_<?php echo $app['id']; ?>" class="form-control">
                                        <option value="NRB Main Office - Lilongwe">NRB Main Office - Lilongwe</option>
                                        <option value="NRB Blantyre Office">NRB Blantyre Office</option>
                                        <option value="NRB Mzuzu Office">NRB Mzuzu Office</option>
                                        <option value="NRB Zomba Office">NRB Zomba Office</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="admin_notes_<?php echo $app['id']; ?>">Admin Notes:</label>
                                <textarea name="admin_notes" id="admin_notes_<?php echo $app['id']; ?>" class="form-control" rows="3" placeholder="Add any notes or comments for this status update..."></textarea>
                            </div>
                            
                            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                                <button type="submit" name="update_status" class="btn btn-primary">
                                    Update Status & Send Notification
                                </button>
                                <a href="view_documents.php?app_id=<?php echo $app['id']; ?>" class="btn btn-success" target="_blank">
                                    View Documents
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <script>
        // Auto-hide success/error messages after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);

        // Show/hide collection center field based on status
        document.querySelectorAll('select[name="new_status"]').forEach(select => {
            select.addEventListener('change', function() {
                const collectionGroup = this.closest('form').querySelector('select[name="collection_center"]').closest('.form-group');
                if (this.value === 'ready') {
                    collectionGroup.style.display = 'block';
                    collectionGroup.querySelector('select').required = true;
                } else {
                    collectionGroup.style.display = 'none';
                    collectionGroup.querySelector('select').required = false;
                }
            });
        });
    </script>
</body>
</html>