<?php
// admin_update_application_status.php
// This file handles admin updating application status and triggers notifications

session_start();
require_once 'database.php';
require_once 'notification_system.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $application_id = $_POST['application_id'];
    $new_status = $_POST['new_status'];
    $rejection_reason = $_POST['rejection_reason'] ?? '';
    $collection_center = $_POST['collection_center'] ?? 'NRB Main Office';
    
    try {
        $database = new Database();
        $db = $database->getConnection();
        
        // Get current application details
        $query = "SELECT user_id, reference_number, status FROM applications WHERE id = :application_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':application_id', $application_id);
        $stmt->execute();
        $application = $stmt->fetch();
        
        if ($application) {
            $user_id = $application['user_id'];
            $reference_number = $application['reference_number'];
            $old_status = $application['status'];
            
            // Update application status
            $update_query = "UPDATE applications 
                           SET status = :new_status, 
                               collection_center = :collection_center,
                               admin_notes = :rejection_reason,
                               updated_at = NOW() 
                           WHERE id = :application_id";
            
            $update_stmt = $db->prepare($update_query);
            $update_stmt->bindParam(':new_status', $new_status);
            $update_stmt->bindParam(':collection_center', $collection_center);
            $update_stmt->bindParam(':rejection_reason', $rejection_reason);
            $update_stmt->bindParam(':application_id', $application_id);
            
            if ($update_stmt->execute()) {
                // Initialize notification system
                $notifier = new NotificationSystem();
                
                // Send appropriate notification based on new status
                switch ($new_status) {
                    case 'under_review':
                        $notifier->notifyApplicationUnderReview($user_id, $reference_number);
                        break;
                        
                    case 'verified':
                    case 'approved':
                        $notifier->notifyApplicationApproved($user_id, $reference_number);
                        break;
                        
                    case 'rejected':
                        $notifier->notifyApplicationRejected($user_id, $reference_number, $rejection_reason);
                        break;
                        
                    case 'ready':
                        $notifier->notifyIdReadyForCollection($user_id, $reference_number, $collection_center);
                        break;
                }
                
                $_SESSION['success_message'] = "Application status updated successfully and notification sent to user.";
                header('Location: admin_dashboard.php');
                exit();
            } else {
                $_SESSION['error_message'] = "Failed to update application status.";
            }
        } else {
            $_SESSION['error_message'] = "Application not found.";
        }
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Database error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Application Status</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background: #f5f5f5;
        }
        .form-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        select, textarea, input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            background: #2c5aa0;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
        }
        button:hover {
            background: #1e4080;
        }
        .rejection-reason {
            display: none;
        }
        .collection-center {
            display: none;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Update Application Status</h2>
        
        <form method="POST" action="">
            <input type="hidden" name="application_id" value="<?php echo $_GET['app_id'] ?? ''; ?>">
            
            <div class="form-group">
                <label>New Status:</label>
                <select name="new_status" id="statusSelect" required>
                    <option value="">Select Status</option>
                    <option value="under_review">Under Review</option>
                    <option value="verified">Verified/Approved</option>
                    <option value="rejected">Rejected</option>
                    <option value="ready">Ready for Collection</option>
                </select>
            </div>
            
            <div class="form-group rejection-reason" id="rejectionReasonDiv">
                <label>Rejection Reason:</label>
                <textarea name="rejection_reason" id="rejectionReason" rows="4" placeholder="Enter reason for rejection..."></textarea>
            </div>
            
            <div class="form-group collection-center" id="collectionCenterDiv">
                <label>Collection Center:</label>
                <select name="collection_center">
                    <option value="NRB Main Office - Lilongwe">NRB Main Office - Lilongwe</option>
                    <option value="NRB Blantyre Office">NRB Blantyre Office</option>
                    <option value="NRB Mzuzu Office">NRB Mzuzu Office</option>
                    <option value="NRB Zomba Office">NRB Zomba Office</option>
                </select>
            </div>
            
            <button type="submit" name="update_status">Update Status & Send Notification</button>
        </form>
    </div>
    
    <script>
        document.getElementById('statusSelect').addEventListener('change', function() {
            const status = this.value;
            const rejectionDiv = document.getElementById('rejectionReasonDiv');
            const collectionDiv = document.getElementById('collectionCenterDiv');
            const rejectionField = document.getElementById('rejectionReason');
            
            // Hide all conditional fields first
            rejectionDiv.style.display = 'none';
            collectionDiv.style.display = 'none';
            rejectionField.required = false;
            
            // Show relevant fields based on status
            if (status === 'rejected') {
                rejectionDiv.style.display = 'block';
                rejectionField.required = true;
            } else if (status === 'ready') {
                collectionDiv.style.display = 'block';
            }
        });
    </script>
</body>
</html>