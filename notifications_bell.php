<div class="notification-container">
    <button class="notification-btn" id="notificationBtn">
        <i class="fas fa-bell"></i>
        <span class="notification-badge" id="notificationBadge" style="display: none;">0</span>
    </button>

    <div class="notification-dropdown" id="notificationDropdown">
        <div class="notification-header">
            <span class="notification-title">Notifications</span>
            <a href="#" class="mark-all-read" id="markAllRead">Mark all read</a>
        </div>
        <div class="notification-list" id="notificationList">
            <div class="no-notifications">
                <i class="fas fa-bell-slash"></i>
                <h4>No notifications yet</h4>
                <p>You'll receive updates here</p>
            </div>
        </div>
    </div>
</div>

<script>
(async function(){
    const notificationBtn = document.getElementById('notificationBtn');
    const notificationDropdown = document.getElementById('notificationDropdown');
    const notificationBadge = document.getElementById('notificationBadge');
    const notificationList = document.getElementById('notificationList');
    const markAllReadBtn = document.getElementById('markAllRead');

    notificationBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        notificationDropdown.classList.toggle('show');
        fetchNotifications();
    });

    document.addEventListener('click', (e) => {
        if(!e.target.closest('.notification-container')){
            notificationDropdown.classList.remove('show');
        }
    });

    markAllReadBtn.addEventListener('click', async () => {
        await fetch('api_mark_all_read.php', {method:'POST'});
        fetchNotifications();
    });

    async function fetchNotifications() {
        try {
            const res = await fetch('api_fetch_notifications.php');
            const data = await res.json();
            
            if (!data.success) return;

            // Update badge
            if(data.unread > 0){
                notificationBadge.textContent = data.unread > 99 ? '99+' : data.unread;
                notificationBadge.style.display = 'flex';
            } else {
                notificationBadge.style.display = 'none';
            }

            // Populate dropdown
            notificationList.innerHTML = '';
            if(data.notifications.length === 0){
                notificationList.innerHTML = `<div class="no-notifications">
                    <i class="fas fa-bell-slash"></i>
                    <h4>No notifications yet</h4>
                    <p>You'll receive updates here</p>
                </div>`;
                return;
            }

            data.notifications.forEach(n => {
                const item = document.createElement('div');
                item.className = 'notification-item' + (n.status === 'unread' ? ' unread' : '');
                item.innerHTML = `<div class="notification-content">
                    <h4>${n.title}</h4>
                    <p>${n.message}</p>
                    <small>${new Date(n.created_at).toLocaleString()}</small>
                </div>`;
                item.addEventListener('click', async () => {
                    await fetch('api_mark_read.php',{
                        method:'POST',
                        headers:{'Content-Type':'application/x-www-form-urlencoded'},
                        body:'id=' + encodeURIComponent(n.id)
                    });
                    fetchNotifications();
                });
                notificationList.appendChild(item);
            });
        } catch(err){
            console.error(err);
        }
    }

    // Auto-refresh every 10 sec
    setInterval(fetchNotifications, 10000);
})();
</script>
