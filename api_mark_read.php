<?php
session_start();
include 'includes/notifications.php';

$id = $_POST['id'] ?? 0;
if($id > 0){
    markNotificationRead($id);
    echo json_encode(['success'=>true]);
}else{
    echo json_encode(['success'=>false]);
}
?>
