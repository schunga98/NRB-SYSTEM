<?php
session_start();

// Simulate logged-in user
$_SESSION['user_id'] = 1; // replace with real session after login

include 'includes/notifications.php';

// Fetch notifications
$notifications = getNotifications($_SESSION['user_id'], 10);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Notifications</title>
</head>
<body>
<!-- Notifications Bell -->
<div id="notif-wrapper" style="position:relative; display:inline-block; margin:20px;">
    <button id="notif-bell" style="background:none; border:none; cursor:pointer; font-size:24px;">
        🔔 <span id="notif-count" style="background:red; color:white; border-radius:50%; padding:2px 6px; font-size:12px; min-width:22px; display:inline-block; text-align:center;">0</span>
    </button>

    <div id="notif-dropdown" style="display:none; position:absolute; right:0; top:30px; width:320px; border:1px solid #ddd; background:#fff; box-shadow:0 6px 12px rgba(0,0,0,0.1); z-index:1000;">
        <div style="padding:8px; border-bottom:1px solid #eee; display:flex; justify-content:space-between; align-items:center;">
            <strong>Notifications</strong>
            <button id="mark-all-btn" style="background:none; border:none; color:#007bff; cursor:pointer;">Mark all read</button>
        </div>
        <div id="notif-list" style="max-height:360px; overflow:auto;"></div>
        <div style="padding:8px; border-top:1px solid #eee; text-align:center;">
            <a href="view_notifications.php">View all</a>
        </div>
    </div>
</div>

    <h2>My Notifications</h2>
    <?php if (empty($notifications)) : ?>
        <p>No notifications found.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($notifications as $n): ?>
                <li style="margin-bottom:10px; padding:8px; border:1px solid #ccc; <?php if($n['status']=='unread'){echo 'background:#f0f8ff;';} ?>">
                    <strong><?php echo $n['title']; ?></strong><br>
                    <?php echo $n['message']; ?><br>
                    <small>Received: <?php echo $n['created_at']; ?></small>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <script>
(function(){
    const bell = document.getElementById('notif-bell');
    const dropdown = document.getElementById('notif-dropdown');
    const countEl = document.getElementById('notif-count');
    const listEl = document.getElementById('notif-list');
    const markAllBtn = document.getElementById('mark-all-btn');

    let visible = false;

    bell.addEventListener('click', () => {
        visible = !visible;
        dropdown.style.display = visible ? 'block' : 'none';
        if (visible) fetchNotifications();
    });

    markAllBtn.addEventListener('click', async () => {
        await fetch('api_mark_all_read.php', { method:'POST' });
        fetchNotifications();
    });

    async function fetchNotifications(){
        try {
            const res = await fetch('api_fetch_notifications.php'); // we'll create this API next
            const json = await res.json();
            if (!json.success) return;

            countEl.innerText = json.unread;
            listEl.innerHTML = '';

            if (json.notifications.length === 0) {
                listEl.innerHTML = '<div style="padding:12px;color:#666">No notifications</div>';
                return;
            }

            json.notifications.forEach(n => {
                const item = document.createElement('div');
                item.style.padding = '10px';
                item.style.borderBottom = '1px solid #f1f1f1';
                if(n.status === 'unread') item.style.background = '#f9f9ff';
                item.innerHTML = `<strong>${n.title}</strong><br>${n.message}<br><small>${new Date(n.created_at).toLocaleString()}</small>`;

                item.style.cursor = 'pointer';
                item.addEventListener('click', async () => {
                    // mark as read
                    await fetch('api_mark_read.php', {
                        method: 'POST',
                        headers: {'Content-Type':'application/x-www-form-urlencoded'},
                        body: 'id=' + encodeURIComponent(n.id)
                    });
                    fetchNotifications();
                });

                listEl.appendChild(item);
            });
        } catch(err) {
            console.error('Fetch error:', err);
        }
    }

    // auto refresh every 10 seconds
    setInterval(fetchNotifications, 10000);

    // initial fetch
    fetchNotifications();
})();
</script>

</body>
</html>
