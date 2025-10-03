<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    // Dummy admin session for testing
    $_SESSION['admin_id'] = 1;
    $_SESSION['admin_name'] = 'Admin User';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NRB System - Admin Dashboard</title>
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
            width: 280px;
            height: 100vh;
            background: linear-gradient(135deg, #1e4080 0%, #0d2347 100%);
            padding: 0;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            z-index: 1000;
            overflow-y: auto;
        }

        .admin-logo {
            background: rgba(255,255,255,0.1);
            color: white;
            text-align: center;
            padding: 30px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .admin-logo img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-bottom: 15px;
            border: 3px solid white;
            background: white;
            padding: 5px;
        }

        .admin-logo h2 {
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .admin-logo p {
            font-size: 0.85rem;
            opacity: 0.9;
        }

        .menu-title {
            color: rgba(255,255,255,0.6);
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 20px 20px 10px;
            margin-top: 10px;
        }

        .nav-menu {
            list-style: none;
            padding: 0 15px 20px;
        }

        .nav-item {
            margin-bottom: 5px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 15px 15px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            border-radius: 10px;
            transition: all 0.3s ease;
            position: relative;
            font-weight: 500;
        }

        .nav-link:hover {
            background: rgba(255,255,255,0.1);
            color: white;
            transform: translateX(5px);
        }

        .nav-link.active {
            background: linear-gradient(135deg, #F7CE5F 0%, #f5b82e 100%);
            color: #190B02;
            font-weight: 600;
        }

        .nav-link i {
            font-size: 1.2rem;
            width: 35px;
            margin-right: 12px;
        }

        .nav-link span {
            font-size: 1rem;
        }

        .nav-link .badge {
            position: absolute;
            right: 15px;
            background: #ff4757;
            color: white;
            border-radius: 12px;
            padding: 2px 8px;
            font-size: 0.7rem;
            font-weight: bold;
        }

        .main-content {
            margin-left: 280px;
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

        .header-left {
            display: flex;
            align-items: center;
            gap: 20px;
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

        .search-box {
            position: relative;
        }

        .search-box input {
            padding: 10px 40px 10px 15px;
            border: 2px solid #e1e8ed;
            border-radius: 25px;
            width: 300px;
            font-size: 0.9rem;
            transition: all 0.3s;
        }

        .search-box input:focus {
            outline: none;
            border-color: #2c5aa0;
            box-shadow: 0 0 0 3px rgba(44, 90, 160, 0.1);
        }

        .search-box i {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
        }

        .admin-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 15px;
            background: #f8f9fa;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .admin-profile:hover {
            background: #e9ecef;
        }

        .admin-avatar {
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

        .admin-info h4 {
            color: #333;
            font-size: 0.95rem;
            margin-bottom: 2px;
        }

        .admin-info p {
            color: #666;
            font-size: 0.75rem;
        }

        .dashboard-content {
            padding: 30px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
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
            cursor: pointer;
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

        .stat-card.applicants .stat-icon {
            background: #fff3cd;
            color: #856404;
        }

        .stat-card.approved .stat-icon {
            background: #d4edda;
            color: #155724;
        }

        .stat-card.denied .stat-icon {
            background: #f8d7da;
            color: #721c24;
        }

        .stat-card.printed .stat-icon {
            background: #d1ecf1;
            color: #0c5460;
        }

        .stat-card.collected .stat-icon {
            background: #e2e3e5;
            color: #383d41;
        }

        .stat-card.overstayed .stat-icon {
            background: #f8d7da;
            color: #721c24;
        }

        .stat-info h3 {
            font-size: 2rem;
            color: #333;
            margin-bottom: 5px;
        }

        .stat-info p {
            color: #666;
            font-size: 0.9rem;
        }

        .content-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
            overflow: hidden;
        }

        .content-header {
            padding: 25px 30px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .content-header h2 {
            color: #333;
            font-size: 1.5rem;
            margin-bottom: 5px;
        }

        .content-header p {
            color: #666;
            font-size: 0.9rem;
        }

        .filter-buttons {
            display: flex;
            gap: 10px;
        }

        .filter-btn {
            padding: 8px 20px;
            border: 2px solid #e1e8ed;
            background: white;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 500;
        }

        .filter-btn:hover {
            border-color: #2c5aa0;
            color: #2c5aa0;
        }

        .filter-btn.active {
            background: #2c5aa0;
            color: white;
            border-color: #2c5aa0;
        }

        .content-body {
            padding: 30px;
        }

        .table-container {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table thead {
            background: #f8f9fa;
        }

        table th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: #333;
            border-bottom: 2px solid #e9ecef;
        }

        table td {
            padding: 15px;
            border-bottom: 1px solid #f0f0f0;
            color: #666;
        }

        table tr:hover {
            background: #f8f9fa;
        }

        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-approved {
            background: #d4edda;
            color: #155724;
        }

        .status-denied {
            background: #f8d7da;
            color: #721c24;
        }

        .action-btn {
            padding: 6px 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 0.85rem;
            margin-right: 5px;
        }

        .btn-view {
            background: #2c5aa0;
            color: white;
        }

        .btn-view:hover {
            background: #1e4080;
        }

        .btn-approve {
            background: #28a745;
            color: white;
        }

        .btn-approve:hover {
            background: #218838;
        }

        .btn-deny {
            background: #dc3545;
            color: white;
        }

        .btn-deny:hover {
            background: #c82333;
        }

        .mobile-toggle {
            display: none;
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

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .search-box input {
                width: 200px;
            }

            .header-title h1 {
                font-size: 1.3rem;
            }

            .content-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
        }
    </style>
</head>
<body>
    <button class="mobile-toggle" id="mobileToggle">
        <i class="fas fa-bars"></i>
    </button>

    <aside class="sidebar" id="sidebar">
        <div class="admin-logo">
            <img src="images/emblem.png" alt="NRB Logo">
            <h2>NRB ADMIN</h2>
            <p>National Registration Bureau</p>
        </div>

        <div class="menu-title">MAIN MENU</div>

        <ul class="nav-menu">
            <li class="nav-item">
                <a href="application.php" class="nav-link active">
                    <i class="fas fa-users"></i>
                    <span>Applicants</span>
                    <span class="badge">12</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="approved.php" class="nav-link">
                    <i class="fas fa-check-circle"></i>
                    <span>Approved</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="denied.php" class="nav-link">
                    <i class="fas fa-times-circle"></i>
                    <span>Denied</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="printed.php" class="nav-link">
                    <i class="fas fa-print"></i>
                    <span>Printed</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="collected.php" class="nav-link">
                    <i class="fas fa-hand-holding"></i>
                    <span>Collected</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="overstayed.php" class="nav-link">
                    <i class="fas fa-clock"></i>
                    <span>Overstayed</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="statistics.php" class="nav-link">
                    <i class="fas fa-chart-bar"></i>
                    <span>Statistics</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="verification.php" class="nav-link">
                    <i class="fas fa-chart-bar"></i>
                    <span>Verify Applications</span>
                </a>
            </li>

            <li class="nav-item" style="margin-top: 20px;">
                <a href="controllers/logout.php" class="nav-link">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Log Out</span>
                </a>
            </li>
        </ul>
    </aside>

    <main class="main-content">
        <header class="top-header">
            <div class="header-left">
                <div class="header-title">
                    <h1>All Applicants</h1>
                    <p>Manage and review ID renewal applications</p>
                </div>
            </div>

            <div class="header-actions">
                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="Search applicants...">
                    <i class="fas fa-search"></i>
                </div>

                <div class="admin-profile">
                    <div class="admin-avatar">
                        <?php echo strtoupper(substr($_SESSION['admin_name'], 0, 1)); ?>
                    </div>
                    <div class="admin-info">
                        <h4><?php echo htmlspecialchars($_SESSION['admin_name']); ?></h4>
                        <p>Administrator</p>
                    </div>
                </div>
            </div>
        </header>

        <div class="dashboard-content">
            <div class="stats-grid">
                <?php
                $stats = [
                    'totalApplicants' => 156,
                    'totalApproved' => 98,
                    'totalDenied' => 12,
                    'totalPrinted' => 87,
                    'totalCollected' => 65,
                    'totalOverstayed' => 8
                ];
                ?>
                <div class="stat-card applicants">
                    <div class="stat-icon"><i class="fas fa-users"></i></div>
                    <div class="stat-info">
                        <h3><?php echo $stats['totalApplicants']; ?></h3>
                        <p>Total Applicants</p>
                    </div>
                </div>

                <div class="stat-card approved">
                    <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                    <div class="stat-info">
                        <h3><?php echo $stats['totalApproved']; ?></h3>
                        <p>Approved</p>
                    </div>
                </div>

                <div class="stat-card denied">
                    <div class="stat-icon"><i class="fas fa-times-circle"></i></div>
                    <div class="stat-info">
                        <h3><?php echo $stats['totalDenied']; ?></h3>
                        <p>Denied</p>
                    </div>
                </div>

                <div class="stat-card printed">
                    <div class="stat-icon"><i class="fas fa-print"></i></div>
                    <div class="stat-info">
                        <h3><?php echo $stats['totalPrinted']; ?></h3>
                        <p>Printed</p>
                    </div>
                </div>

                <div class="stat-card collected">
                    <div class="stat-icon"><i class="fas fa-hand-holding"></i></div>
                    <div class="stat-info">
                        <h3><?php echo $stats['totalCollected']; ?></h3>
                        <p>Collected</p>
                    </div>
                </div>

                <div class="stat-card overstayed">
                    <div class="stat-icon"><i class="fas fa-exclamation-triangle"></i></div>
                    <div class="stat-info">
                        <h3><?php echo $stats['totalOverstayed']; ?></h3>
                        <p>Overstayed</p>
                    </div>
                </div>
            </div>

            <div class="content-card">
                <div class="content-header">
                    <div>
                        <h2>All Applicants</h2>
                        <p>Review and manage ID renewal applications</p>
                    </div>
                    <div class="filter-buttons">
                        <button class="filter-btn active" onclick="filterTable('all')">All</button>
                        <button class="filter-btn" onclick="filterTable('pending')">Pending</button>
                        <button class="filter-btn" onclick="filterTable('verified')">Verified</button>
                        <button class="filter-btn" onclick="filterTable('processing')">Processing</button>
                    </div>
                </div>

                <div class="content-body">
                    <div class="table-container">
                        <table id="applicantsTable">
                            <thead>
                                <tr>
                                    <th>Ref Number</th>
                                    <th>Full Name</th>
                                    <th>Email</th>
                                    <th>Application Type</th>
                                    <th>Status</th>
                                    <th>Date Submitted</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>NRB-2025-001</td>
                                    <td>Silvester Chunga</td>
                                    <td>silvesterchunga@gmail.com</td>
                                    <td>Renewal</td>
                                    <td><span class="status-badge status-pending">Pending</span></td>
                                    <td>2025-01-15</td>

                                    <td>
                                        <button class="action-btn btn-view"><i class="fas fa-eye"></i> View</button>
                                        <button class="action-btn btn-approve"><i class="fas fa-check"></i> Approve</button>
                                        <button class="action-btn btn-deny"><i class="fas fa-times"></i> Deny</button>
                                    </td>
                                </tr>

                                <tr>
                                      <td>NRB-2025-002</td>
                                            <td>Mable Simkonde</td>
                                             <td>simkonde@gmail.com</td>
                                             <td>Renewal</td>
                                            <td><span class ="status-badge status-pending">Processed</span></td>
                                     <td>2025-02-16</td>

                                     <td>
                                        <button class="action-btn btn-view"><i class="fas fa-eye"></i> View</button>
                                        <button class="action-btn btn-approve"><i class="fas fa-check"></i> Approve</button>
                                        <button class="action-btn btn-deny"><i class="fas fa-times"></i> Deny</button>
                                    </td>
                                </tr>
                                <tr>
                                      <td>NRB-2025-004</td>
                                      <td>Jacquiline Evody</td>
                                      <td>ojacki@gmail.com</td>
                                      <td>Renewal</td>
                                      <td><span class ="status-badge status-pending">Processed</span></td>
                                     <td>2025-02-16</td>

                                     <td>
                                        <button class="action-btn btn-view"><i class="fas fa-eye"></i> View</button>
                                        <button class="action-btn btn-approve"><i class="fas fa-check"></i> Approve</button>
                                        <button class="action-btn btn-deny"><i class="fas fa-times"></i> Deny</button>
                                    </td>
                                </tr>






                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        const mobileToggle = document.getElementById('mobileToggle');
        const sidebar = document.getElementById('sidebar');
        if (mobileToggle) {
            mobileToggle.addEventListener('click', () => sidebar.classList.toggle('active'));
        }

        function filterTable(status) {
            const buttons = document.querySelectorAll('.filter-btn');
            buttons.forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');
            console.log('Filter:', status);
        }

        const searchInput = document.getElementById('searchInput');
        searchInput.addEventListener('input', function(e) {
            const term = e.target.value.toLowerCase();
            document.querySelectorAll('#applicantsTable tbody tr').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(term) ? '' : 'none';
            });
        });
    </script>
</body>
</html>