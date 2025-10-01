<?php
session_start();

// Check if user is logged in
//if (!isset($_SESSION['user_id'])) {
  //  header('Location: login.php');
  //  exit();
//}

// For demonstration, set some default values if not set
if (!isset($_SESSION['full_name'])) {
    $_SESSION['full_name'] = 'John Doe';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NRB System - Citizen Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            min-height: 100vh;
        }

        /* Sidebar Navigation */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 260px;
            height: 100vh;
            background: linear-gradient(135deg, #2c5aa0 0%, #1e4080 100%);
            padding: 20px 0;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            z-index: 1000;
        }

        .logo {
            color: white;
            text-align: center;
            padding: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 20px;
        }

        .logo img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 10px;
            display: block;
            border: 3px solid rgba(255,255,255,0.3);
        }

        .logo i {
            font-size: 3rem;
            margin-bottom: 10px;
            display: block;
        }

        .logo h2 {
            font-size: 1.3rem;
            font-weight: 600;
        }

        .logo p {
            font-size: 0.85rem;
            opacity: 0.8;
            margin-top: 5px;
        }

        .nav-menu {
            list-style: none;
            padding: 0 10px;
        }

        .nav-item {
            margin-bottom: 5px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            color: white;
            text-decoration: none;
            border-radius: 10px;
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-link:hover {
            background: rgba(255,255,255,0.1);
            transform: translateX(5px);
        }

        .nav-link.active {
            background: rgba(255,255,255,0.2);
        }

        .nav-link i {
            font-size: 1.2rem;
            width: 30px;
            margin-right: 15px;
        }

        .nav-link span {
            font-size: 1rem;
            font-weight: 500;
        }

        /* Notification badge on sidebar menu */
        .notification-badge-menu {
            position: absolute;
            right: 15px;
            background: #ff4757;
            color: white;
            border-radius: 50%;
            min-width: 22px;
            height: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            font-weight: bold;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }

        /* Main Content Area */
        .main-content {
            margin-left: 260px;
            padding: 0;
            min-height: 100vh;
        }

        /* Top Header */
        .top-header {
            background: white;
            padding: 20px 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-title h1 {
            color: #333;
            font-size: 1.8rem;
            margin-bottom: 5px;
        }

        .header-title p {
            color: #666;
            font-size: 0.9rem;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 15px;
            background: #f8f9fa;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .user-profile:hover {
            background: #e9ecef;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #2c5aa0 0%, #1e4080 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.1rem;
        }

        .user-info h4 {
            color: #333;
            font-size: 0.95rem;
            margin-bottom: 2px;
        }

        .user-info p {
            color: #666;
            font-size: 0.75rem;
        }

        /* Dashboard Content */
        .dashboard-content {
            padding: 30px;
        }

        .welcome-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .welcome-card h2 {
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .welcome-card p {
            font-size: 1.1rem;
            opacity: 0.95;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            display: flex;
            align-items: center;
            gap: 20px;
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.12);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
        }

        .stat-card.total .stat-icon {
            background: #e3f2fd;
            color: #2c5aa0;
        }

        .stat-card.pending .stat-icon {
            background: #fff3cd;
            color: #856404;
        }

        .stat-card.completed .stat-icon {
            background: #d4edda;
            color: #155724;
        }

        .stat-info h3 {
            font-size: 2rem;
            color: #333;
            margin-bottom: 5px;
        }

        .stat-info p {
            color: #666;
            font-size: 0.95rem;
        }

        .quick-actions {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }

        .quick-actions h3 {
            color: #333;
            margin-bottom: 20px;
            font-size: 1.3rem;
        }

        .action-buttons {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .action-btn {
            padding: 20px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            text-decoration: none;
            color: #333;
            display: flex;
            align-items: center;
            gap: 15px;
            transition: all 0.3s;
            background: white;
        }

        .action-btn:hover {
            border-color: #2c5aa0;
            background: #f8f9ff;
            transform: translateY(-2px);
        }

        .action-btn i {
            font-size: 2rem;
            color: #2c5aa0;
        }

        .action-btn-text h4 {
            font-size: 1rem;
            margin-bottom: 5px;
            color: #333;
        }

        .action-btn-text p {
            font-size: 0.85rem;
            color: #666;
        }

        /* Notification Dropdown */
        .notification-container {
            position: relative;
        }

        .notification-btn {
            background: #f8f9fa;
            border: none;
            color: #333;
            padding: 12px;
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1.2rem;
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .notification-btn:hover {
            background: #e9ecef;
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #000000d7;
            color: white;
            border-radius: 50%;
            min-width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            font-weight: bold;
            border: 2px solid white;
            animation: pulse 2s infinite;
        }

        .notification-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            width: 400px;
            max-width: 90vw;
            background: white;
            border-radius: 12px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            margin-top: 15px;
            max-height: 500px;
            overflow: hidden;
        }

        .notification-dropdown.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .notification-header {
            padding: 1.2rem 1.5rem;
            border-bottom: 1px solid #e0e0e0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f8f9fa;
        }

        .notification-title {
            font-weight: 600;
            color: #333;
            font-size: 1.1rem;
        }

        .mark-all-read {
            color: #2c5aa0;
            text-decoration: none;
            font-size: 0.85rem;
            cursor: pointer;
            padding: 0.4rem 0.8rem;
            border-radius: 6px;
            transition: all 0.2s;
        }

        .mark-all-read:hover {
            background-color: #e3f2fd;
        }

        .notification-list {
            max-height: 350px;
            overflow-y: auto;
        }

        .notification-item {
            padding: 1.2rem 1.5rem;
            border-bottom: 1px solid #f0f0f0;
            cursor: pointer;
            transition: all 0.2s;
        }

        .notification-item:hover {
            background-color: #f8f9fa;
        }

        .notification-item.unread {
            background: #f0f7ff;
            border-left: 4px solid #2c5aa0;
        }

        .notification-content h4 {
            color: #333;
            font-size: 0.95rem;
            margin-bottom: 0.5rem;
        }

        .notification-content p {
            color: #666;
            font-size: 0.85rem;
            line-height: 1.4;
        }

        .no-notifications {
            padding: 3rem 2rem;
            text-align: center;
            color: #666;
        }

        .no-notifications i {
            font-size: 3rem;
            color: #ddd;
            margin-bottom: 1rem;
            display: block;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s;
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .mobile-toggle {
                display: block;
                position: fixed;
                top: 20px;
                left: 20px;
                z-index: 1001;
                background: #2c5aa0;
                color: white;
                border: none;
                width: 45px;
                height: 45px;
                border-radius: 8px;
                font-size: 1.2rem;
                cursor: pointer;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .action-buttons {
                grid-template-columns: 1fr;
            }
        }

        .mobile-toggle {
            display: none;
        }
    </style>
</head>

<body>
    <!-- Mobile Menu Toggle -->
    <button class="mobile-toggle" id="mobileToggle">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar Navigation -->
    <aside class="sidebar" id="sidebar">
        <div class="logo">
            <i class="fas fa-id-card"></i>
            <h2>NRB Portal</h2>
            <p>Citizen Services</p>
        </div>

        <ul class="nav-menu">
            <li class="nav-item">
                <a href="citizen.php" class="nav-link active">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="renewal.php" class="nav-link">
                    <i class="fas fa-file-alt"></i>
                    <span>Apply for Renewal</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="my_applications.php" class="nav-link">
                    <i class="fas fa-list-alt"></i>
                    <span>My Applications</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="notifications.php" class="nav-link">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge-menu" id="sidebarBadge">Notifications</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="profile.php" class="nav-link">
                    <i class="fas fa-user"></i>
                    <span>Profile</span>
                </a>
            </li>
            <li class="nav-item" style="margin-top: 20px;">
                <a href="login.php" class="nav-link">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Header -->
        <header class="top-header">
            <div class="header-title">
                <h1>Dashboard</h1>
                <p>Welcome back, <?php echo htmlspecialchars($_SESSION['full_name']); ?>!</p>
            </div>

            <div class="header-actions">
                <!-- Include Notification Bell -->
                <?php include 'notifications_bell.php'; ?>
                

                <!-- User Profile -->
                <a href="profile.php" class="user-profile">
                    <div class="user-avatar">
                        <?php echo strtoupper(substr($_SESSION['full_name'], 0, 1)); ?>
                    </div>
                    <div class="user-info">
                        <h4><?php echo htmlspecialchars($_SESSION['full_name']); ?></h4>
                        <p>Citizen</p>
                    </div>
                </a>
            </div>
        </header>

        <!-- Dashboard Content -->
        <div class="dashboard-content">
            <!-- Welcome Card -->
            <div class="welcome-card">
                <h2>Welcome to NRB Citizen Portal</h2>
                <p>Apply for ID renewals, track your applications, and manage your profile all in one place.</p>
            </div>

            <!-- Statistics -->
            <div class="stats-grid">
                <div class="stat-card total">
                    <div class="stat-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="stat-info">
                        <h3 id="totalApplications">0</h3>
                        <p>Total Applications</p>
                    </div>
                </div>

                <div class="stat-card pending">
                    <div class="stat-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-info">
                        <h3 id="pendingApplications">0</h3>
                        <p>Pending Applications</p>
                    </div>
                </div>

                <div class="stat-card completed">
                    <div class="stat-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-info">
                        <h3 id="completedApplications">0</h3>
                        <p>Completed</p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="quick-actions">
                <h3>Quick Actions</h3>
                <div class="action-buttons">
                    <a href="renewal.php" class="action-btn">
                        <i class="fas fa-plus-circle"></i>
                        <div class="action-btn-text">
                            <h4>New Application</h4>
                            <p>Apply for ID renewal</p>
                        </div>
                    </a>

                    <a href="my_applications.php" class="action-btn">
                        <i class="fas fa-search"></i>
                        <div class="action-btn-text">
                            <h4>Track Application</h4>
                            <p>Check application status</p>
                        </div>
                    </a>

                    <a href="profile.php" class="action-btn">
                        <i class="fas fa-user-edit"></i>
                        <div class="action-btn-text">
                            <h4>Update Profile</h4>
                            <p>Manage your information</p>
                        </div>
                    </a>

                    <a href="help.php" class="action-btn">
                        <i class="fas fa-question-circle"></i>
                        <div class="action-btn-text">
                            <h4>Help & Support</h4>
                            <p>Get assistance</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Mobile menu toggle
        const mobileToggle = document.getElementById('mobileToggle');
        const sidebar = document.getElementById('sidebar');

        if (mobileToggle) {
            mobileToggle.addEventListener('click', function() {
                sidebar.classList.toggle('active');
            });
        }

        // Load dashboard statistics
        async function loadDashboardStats() {
            try {
                const response = await fetch('get_dashboard_stats.php');
                const data = await response.json();
                
                if (data.success) {
                    document.getElementById('totalApplications').textContent = data.stats.total || '0';
                    document.getElementById('pendingApplications').textContent = data.stats.pending || '0';
                    document.getElementById('completedApplications').textContent = data.stats.completed || '0';
                }
            } catch (error) {
                console.error('Error loading dashboard stats:', error);
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadDashboardStats();
        });
    </script>
</body>
</html>