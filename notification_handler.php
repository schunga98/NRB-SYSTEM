<?php
// notification_handler.php - Complete Notification System

require_once 'database.php';

class NotificationHandler {
    private $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }
    
    // Create notification when application status changes
    public function createStatusChangeNotification($user_id, $application_id, $old_status, $new_status) {
        $notifications = $this->generateStatusNotifications($old_status, $new_status, $application_id);
        
        foreach ($notifications as $notification) {
            $this->createNotification(
                $user_id, 
                $notification['title'], 
                $notification['message'], 
                $notification['type']
            );
        }
        
        return true;
    }
    
    // Generate appropriate notifications based on status change
    private function generateStatusNotifications($old_status, $new_status, $application_id) {
        $notifications = [];
        
        switch ($new_status) {
            case 'verified':
                $notifications[] = [
                    'title' => 'Application Verified ✓',
                    'message' => "Great news! Your ID renewal application (Ref: $application_id) has been successfully verified by our admin team. Your application is now being processed.",
                    'type' => 'success'
                ];
                break;
                
            case 'processing':
                $notifications[] = [
                    'title' => 'ID Processing Started 🔄',
                    'message' => "Your ID renewal application (Ref: $application_id) has been received by NRB and is now in the production queue. We'll notify you once it's ready for collection.",
                    'type' => 'info'
                ];
                break;
                
            case 'ready':
                // Get collection center info
                $collection_center = $this->getCollectionCenter($application_id);
                $notifications[] = [
                    'title' => 'ID Ready for Collection! 🎉',
                    'message' => "Excellent! Your new National ID is ready for collection at $collection_center. Please bring your receipt and original documents. Collection hours: 8:00 AM - 5:00 PM, Monday to Friday.",
                    'type' => 'success'
                ];
                break;
                
            case 'rejected':
                $notifications[] = [
                    'title' => 'Application Requires Attention ⚠️',
                    'message' => "Your ID renewal application (Ref: $application_id) needs additional review. Please check the admin comments and resubmit if necessary.",
                    'type' => 'warning'
                ];
                break;
                
            case 'collected':
                $notifications[] = [
                    'title' => 'ID Successfully Collected! ✅',
                    'message' => "Thank you for using the NRB online system. Your ID renewal process is now complete. We hope you had a great experience with our digital service.",
                    'type' => 'success'
                ];
                break;
                
            default:
                $notifications[] = [
                    'title' => 'Application Status Updated',
                    'message' => "Your application (Ref: $application_id) status has been updated to: " . ucfirst($new_status),
                    'type' => 'info'
                ];
        }
        
        return $notifications;
    }
    
    // Create individual notification
    public function createNotification($user_id, $title, $message, $type = 'info') {
        try {
            $query = "INSERT INTO notifications (user_id, title, message, type, is_read, created_at) 
                      VALUES (:user_id, :title, :message, :type, 0, NOW())";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':message', $message);
            $stmt->bindParam(':type', $type);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Notification creation failed: " . $e->getMessage());
            return false;
        }
    }
    
    // Get user's unread notification count
    public function getUnreadCount($user_id) {
        try {
            $query = "SELECT COUNT(*) as count FROM notifications 
                      WHERE user_id = :user_id AND is_read = 0";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
            
            $result = $stmt->fetch();
            return $result['count'] ?? 0;
        } catch (PDOException $e) {
            error_log("Get unread count failed: " . $e->getMessage());
            return 0;
        }
    }
    
    // Get user's recent notifications
    public function getRecentNotifications($user_id, $limit = 10) {
        try {
            $query = "SELECT id, title, message, type, is_read, created_at 
                      FROM notifications 
                      WHERE user_id = :user_id 
                      ORDER BY created_at DESC 
                      LIMIT :limit";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Get notifications failed: " . $e->getMessage());
            return [];
        }
    }
    
    // Mark notification as read
    public function markAsRead($notification_id, $user_id) {
        try {
            $query = "UPDATE notifications 
                      SET is_read = 1 
                      WHERE id = :notification_id AND user_id = :user_id";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':notification_id', $notification_id);
            $stmt->bindParam(':user_id', $user_id);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Mark as read failed: " . $e->getMessage());
            return false;
        }
    }
    
    // Mark all notifications as read
    public function markAllAsRead($user_id) {
        try {
            $query = "UPDATE notifications SET is_read = 1 WHERE user_id = :user_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $user_id);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Mark all as read failed: " . $e->getMessage());
            return false;
        }
    }
    
    // Get collection center for application
    private function getCollectionCenter($application_id) {
        try {
            $query = "SELECT collection_center FROM applications WHERE id = :application_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':application_id', $application_id);
            $stmt->execute();
            
            $result = $stmt->fetch();
            return $result['collection_center'] ?? 'NRB Main Office';
        } catch (PDOException $e) {
            return 'NRB Main Office';
        }
    }
}

// API endpoints for AJAX calls

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'GET') {
    session_start();
    
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }
    
    $notification = new NotificationHandler();
    $user_id = $_SESSION['user_id'];
    
    $action = $_GET['action'] ?? $_POST['action'] ?? '';
    
    switch ($action) {
        case 'get_unread_count':
            $count = $notification->getUnreadCount($user_id);
            echo json_encode(['count' => $count]);
            break;
            
        case 'get_notifications':
            $notifications = $notification->getRecentNotifications($user_id);
            echo json_encode(['notifications' => $notifications]);
            break;
            
        case 'mark_as_read':
            $notification_id = $_POST['notification_id'] ?? 0;
            $success = $notification->markAsRead($notification_id, $user_id);
            echo json_encode(['success' => $success]);
            break;
            
        case 'mark_all_read':
            $success = $notification->markAllAsRead($user_id);
            echo json_encode(['success' => $success]);
            break;
            
        default:
            http_response_code(400);
            echo json_encode(['error' => 'Invalid action']);
    }
    exit;
}

// Usage example: Call this when admin updates application status
// Example in your admin application processing file:
/*
// When admin changes application status
$old_status = 'submitted';
$new_status = 'verified';
$application_id = 'NRB-2025-000123';
$user_id = 456; // Get from application table

$notificationHandler = new NotificationHandler();
$notificationHandler->createStatusChangeNotification($user_id, $application_id, $old_status, $new_status);
*/
?>