



<?php
session_start();

// Check if user is logged in
//if (!isset($_SESSION['user_id'])) {
 //   header('Location: login.php');
  //  exit();
//}

if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1; // assume user ID 1 exists
    $_SESSION['full_name'] = 'John Doe';
}
$user_id = $_SESSION['user_id'];

// For demonstration
if (!isset($_SESSION['full_name'])) {
    $_SESSION['full_name'] = 'John Doe';
}

// Database connection
require_once 'includes/database.php';

$user_id = $_SESSION['user_id'];
$applications = [];

try {
    $database = new Database();
    $db = $database->getConnection();
    
    // Fetch user's applications
    $query = "SELECT id, reference_number, status, submitted_at, updated_at 
              FROM applications 
              WHERE user_id = :user_id 
              ORDER BY submitted_at DESC";
    
    $stmt = $db->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    
    $applications = $stmt->fetchAll();
} catch (PDOException $e) {
    $error_message = "Error loading applications: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NRB System - My Applications</title>
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

        .main-content {
            margin-left: 260px;
            padding: 0;
            min-height: 100vh;
        }

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

        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 15px;
            background: #f8f9fa;
            border-radius: 25px;
            text-decoration: none;
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

        .content-area {
            padding: 30px;
        }

        .page-header {
            background: white;
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-header h2 {
            color: #2c5aa0;
            font-size: 2rem;
            margin-bottom: 5px;
        }

        .page-header p {
            color: #666;
            font-size: 1rem;
        }

        .back-btn {
            background: #6c757d;
            color: white;
            padding: 12px 30px;
            border-radius: 8px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s;
            font-weight: 600;
        }

        .back-btn:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }

        .applications-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 20px;
        }

        .application-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            transition: all 0.3s;
            border-left: 5px solid #2c5aa0;
        }

        .application-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        }

        .app-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }

        .app-id {
            font-family: monospace;
            font-size: 1.2rem;
            font-weight: bold;
            color: #2c5aa0;
        }

        .status-badge {
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-submitted {
            background: #fff3cd;
            color: #856404;
        }

        .status-under_review {
            background: #d1ecf1;
            color: #0c5460;
        }

        .status-verified, .status-approved {
            background: #d4edda;
            color: #155724;
        }

        .status-processing {
            background: #cce5ff;
            color: #004085;
        }

        .status-ready {
            background: #d4edda;
            color: #155724;
        }

        .status-rejected {
            background: #f8d7da;
            color: #721c24;
        }

        .status-collected {
            background: #e2e3e5;
            color: #383d41;
        }

        .app-details {
            margin-bottom: 20px;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #f5f5f5;
        }

        .detail-label {
            color: #666;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .detail-value {
            color: #333;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .app-actions {
            display: flex;
            gap: 10px;
        }

        .action-btn {
            flex: 1;
            padding: 10px 15px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
            text-decoration: none;
            text-align: center;
            font-size: 0.9rem;
        }

        .btn-view {
            background: #2c5aa0;
            color: white;
        }

        .btn-view:hover {
            background: #1e4080;
        }

        .btn-track {
            background: #f8f9fa;
            color: #333;
            border: 2px solid #e9ecef;
        }

        .btn-track:hover {
            background: #e9ecef;
        }

        .empty-state {
            background: white;
            border-radius: 12px;
            padding: 60px 30px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }

        .empty-state i {
            font-size: 5rem;
            color: #ddd;
            margin-bottom: 20px;
        }

        .empty-state h3 {
            color: #666;
            margin-bottom: 15px;
            font-size: 1.5rem;
        }

        .empty-state p {
            color: #999;
            margin-bottom: 30px;
        }

        .apply-btn {
            background: #2c5aa0;
            color: white;
            padding: 15px 40px;
            border-radius: 8px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .apply-btn:hover {
            background: #1e4080;
            transform: translateY(-2px);
        }

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

            .applications-grid {
                grid-template-columns: 1fr;
            }

            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
        }

        .mobile-toggle {
            display: none;
        }
    </style>
</head>
<body>
    <button class="mobile-toggle" id="mobileToggle">
        <i class="fas fa-bars"></i>
    </button>

    <aside class="sidebar" id="sidebar">
        <div class="logo">
            <img src="images/emblem.png" alt="NRB Logo">
            <h2>NRB Portal</h2>
            <p>Citizen Services</p>
        </div>

        <ul class="nav-menu">
            <li class="nav-item">
                <a href="citizen.php" class="nav-link">
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
                <a href="my_applications.php" class="nav-link active">
                    <i class="fas fa-list-alt"></i>
                    <span>My Applications</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="notifications.php" class="nav-link">
                    <i class="fas fa-bell"></i>
                    <span>Notifications</span>
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

    <main class="main-content">
        <header class="top-header">
            <div class="header-title">
                <h1>My Applications</h1>
                <p>Track and manage your ID renewal applications</p>
            </div>

            <a href="profile.php" class="user-profile">
                <div class="user-avatar">
                    <?php echo strtoupper(substr($_SESSION['full_name'], 0, 1)); ?>
                </div>
                <div class="user-info">
                    <h4><?php echo htmlspecialchars($_SESSION['full_name']); ?></h4>
                    <p>Citizen</p>
                </div>
            </a>
        </header>

        <div class="content-area">
            <div class="page-header">
                <div>
                    <h2>Application Status</h2>
                    <p>View all your submitted applications and their current status</p>
                </div>
                <a href="citizen.php" class="back-btn">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
            </div>

            <?php if (isset($error_message)): ?>
                <div class="empty-state">
                    <i class="fas fa-exclamation-triangle"></i>
                    <h3>Error Loading Applications</h3>
                    <p><?php echo htmlspecialchars($error_message); ?></p>
                    <a href="citizen.php" class="back-btn">Back to Dashboard</a>
                </div>
            <?php elseif (empty($applications)): ?>
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <h3>No Applications Found</h3>
                    <p>You haven't submitted any applications yet. Start by applying for an ID renewal.</p>
                    <a href="renewal.php" class="apply-btn">
                        <i class="fas fa-plus-circle"></i> Apply for Renewal
                    </a>
                </div>
            <?php else: ?>
                <div class="applications-grid">
                    <?php foreach ($applications as $app): ?>
                        <div class="application-card">
                            <div class="app-header">
                                <div class="app-id"><?php echo htmlspecialchars($app['reference_number']); ?></div>
                                <div class="status-badge status-<?php echo $app['status']; ?>">
                                    <?php echo ucwords(str_replace('_', ' ', $app['status'])); ?>
                                </div>
                            </div>

                            <div class="app-details">
                                <div class="detail-row">
                                    <span class="detail-label"><i class="fas fa-calendar-alt"></i> Submission Date:</span>
                                    <span class="detail-value"><?php echo date('M d, Y', strtotime($app['submitted_at'])); ?></span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label"><i class="fas fa-clock"></i> Last Updated:</span>
                                    <span class="detail-value"><?php echo date('M d, Y', strtotime($app['updated_at'])); ?></span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label"><i class="fas fa-hashtag"></i> Application ID:</span>
                                    <span class="detail-value">#<?php echo $app['id']; ?></span>
                                </div>
                            </div>

                            <div class="app-actions">
                                <a href="view_application.php?id=<?php echo $app['id']; ?>" class="action-btn btn-view">
                                    <i class="fas fa-eye"></i> View Details
                                </a>
                                <a href="track_status.php?id=<?php echo $app['id']; ?>" class="action-btn btn-track">
                                    <i class="fas fa-route"></i> Track
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <script>
        const mobileToggle = document.getElementById('mobileToggle');
        const sidebar = document.getElementById('sidebar');

        if (mobileToggle) {
            mobileToggle.addEventListener('click', function() {
                sidebar.classList.toggle('active');
            });
        }

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.sidebar') && !e.target.closest('.mobile-toggle')) {
                sidebar.classList.remove('active');
            }
        });
    </script>
</body>
</html>