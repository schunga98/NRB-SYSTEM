<?php
include 'includes/database.php';
$conn = (new Database())->getConnection();

// Replace with the correct citizen user_id
$user_id = 1; 
$title = "Test Alert";
$message = "Your ID application is ready!";
$status = "unread";
$created_at = date('Y-m-d H:i:s');

$stmt = $conn->prepare("INSERT INTO notifications (user_id, title, message, status, created_at) VALUES (?, ?, ?, ?, ?)");
$stmt->execute([$user_id, $title, $message, $status, $created_at]);

echo "Test notification inserted!";
?>
