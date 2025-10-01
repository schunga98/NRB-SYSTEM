<?php
session_start();
include 'includes/notifications.php';

$user_id = $_SESSION['user_id'] ?? 1; // replace with real session user_id

$notifications = getNotifications($user_id, 10);
$unread = 0;
foreach($notifications as $n){
    if($n['status'] === 'unread') $unread++;
}

echo json_encode([
    'success' => true,
    'unread' => $unread,
    'notifications' => $notifications
]);
?>
