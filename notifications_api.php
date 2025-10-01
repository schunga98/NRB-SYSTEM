<?php
// notification_api.php - API to handle notification requests
session_start();
require_once 'database.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized', 'success' => false]);
    exit();
}

$user_id = $_SESSION['user_id'];
$action = $_GET['action'] ?? $_POST['action'] ?? '';

try {
    $database = new Database();
    $db = $database->getConnection();
    
    switch ($action) {
        case 'get_unread_count':
            // Get count of unread notifications
            $query = "SELECT COUNT(*) as count FROM notifications 
                      WHERE user_id = :user_id AND is_read = 0";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
            $result = $stmt->fetch();
            
            echo json_encode([
                'success' => true,
                'count' => (int)$result['count']
            ]);
            break;
            
        case 'get_notifications':
            // Get all notifications for user
            $limit = $_GET['limit'] ?? 20;
            
            $query = "SELECT id, title, message, type, is_read, created_at 
                      FROM notifications 
                      WHERE user_id = :user_id 
                      ORDER BY created_at DESC 
                      LIMIT :limit";
            
            $stmt = $db->prepare($query);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            
            $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo json_encode([
                'success' => true,
                'notifications' => $notifications
            ]);
            break;
            
        case 'mark_as_read':
            // Mark single notification as read
            $notification_id = $_POST['notification_id'] ?? 0;
            
            $query = "UPDATE notifications 
                      SET is_read = 1 
                      WHERE id = :notification_id AND user_id = :user_id";
            
            $stmt = $db->prepare($query);
            $stmt->bindParam(':notification_id', $notification_id);
            $stmt->bindParam(':user_id', $user_id);
            
            $success = $stmt->execute();
            
            echo json_encode([
                'success' => $success
            ]);
            break;
            
        case 'mark_all_read':
            // Mark all notifications as read
            $query = "UPDATE notifications 
                      SET is_read = 1 
                      WHERE user_id = :user_id";
            
            $stmt = $db->prepare($query);
            $stmt->bindParam(':user_id', $user_id);
            
            $success = $stmt->execute();
            
            echo json_encode([
                'success' => $success
            ]);
            break;
            
        default:
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'error' => 'Invalid action'
            ]);
    }
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Database error: ' . $e->getMessage()
    ]);
}
?>