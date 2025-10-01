<?php
session_start();
$_SESSION['user_id'] = 3; // simulate logged-in user

include 'includes/notifications.php';

// Mark a single notification as read (change the ID to a real one)
$notification_id = 1; // example
if (markNotificationRead($notification_id)) {
    echo "✅ Notification marked as read!";
} else {
    echo "❌ Failed to mark notification as read.";
}

// Optionally, mark all notifications as read
// if (markAllNotificationsRead($_SESSION['user_id'])) {
//     echo "✅ All notifications marked as read!";
// }
?>
