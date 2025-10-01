<?php
session_start();
include 'includes/notifications.php';

$user_id = $_SESSION['user_id'] ?? 1;
markAllNotificationsRead($user_id);

echo json_encode(['success'=>true]);
?>
