<?php
// test_notification.php

include 'includes/notifications.php';

if (createNotification(1, "System Update", "Your application is being processed.")) {
    echo "✅ Notification inserted successfully!";
} else {
    echo "❌ Failed to insert notification.";
}
?>
