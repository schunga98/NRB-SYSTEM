<?php
// Start session at the top
session_start();

// Dummy admin session for testing purposes
if (!isset($_SESSION['admin_id'])) {
    $_SESSION['admin_id'] = 1; // Dummy ID
}
if (!isset($_SESSION['admin_name'])) {
    $_SESSION['admin_name'] = 'Admin User'; // Dummy name
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
        /* --- Styles truncated for brevity --- */
        /* Keep all the CSS you already had here */
    </style>
</head>
<body>
    <!-- Mobile Menu Toggle -->
    <button class="mobile-toggle" id="mobileToggle">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar Navigation -->
    <aside class="sidebar" id="sidebar">
        <div class="admin-logo">
            <img src="images/emblem.png" alt="NRB Logo">
            <h2>NRB ADMIN</h2>
            <p>National Registration Bureau</p>
        </div>

        <div class="menu-title">MAIN MENU</div>

        <ul class="nav-menu">
            <li class="nav-item">
                <a href="admin_dashboard.php" class="nav-link active" id="applicants">
                    <i class="fas fa-users"></i>
                    <span>Applicants</span>
                    <span class="badge">12</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="approved.php" class="nav-link" id="approved">
                    <i class="fas fa-check-circle"></i>
                    <span>Approved</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="denied.php" class="nav-link" id="denied">
                    <i class="fas fa-times-circle"></i>
                    <span>Denied</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="printed.php" class="nav-link" id="printed">
                    <i class="fas fa-print"></i>
                    <span>Printed</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="collected.php" class="nav-link" id="collected">
                    <i class="fas fa-hand-holding"></i>
                    <span>Collected</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="overstayed.php" class="nav-link" id="overstayed">
                    <i class="fas fa-clock"></i>
                    <span>Overstayed</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="statistics.php" class="nav-link" id="statistics">
                    <i class="fas fa-chart-bar"></i>
                    <span>Statistics</span>
                </a>
            </li>
            <li class="nav-item" style="margin-top: 20px;">
                <a href="controllers/logout.php" class="nav-link" id="logout">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Log Out</span>
                </a>
            </li>
        </ul>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Header -->
        <header class="top-header">
            <div class="header-left">
                <div class="header-title">
                    <h1>All Applicants</h1>
                    <p>Manage and review ID renewal applications</p>
                </div>
            </div>

            <div class="header-actions">
                <!-- Search Box -->
                <div class="search-box">
                    <input type="text" placeholder="Search applicants...">
                    <i class="fas fa-search"></i>
                </div>

                <!-- Admin Profile -->
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

        <!-- Dashboard Content -->
        <div class="dashboard-content">
            <!-- Statistics Cards -->
            <div class="stats-grid">
                <?php
                // Sample statistics for testing
                $stats = [
                    'totalApplicants' => 156,
                    'totalApproved' => 98,
                    'totalDenied' => 12,
                    'totalPrinted' => 87,
                    'totalCollected' => 65,
                    'totalOverstayed' => 8
                ];
                ?>
                <div class="stat-card applicants" onclick="filterApplications('all')">
                    <div class="stat-icon"><i class="fas fa-users"></i></div>
                    <div class="stat-info">
                        <h3 id="totalApplicants"><?php echo $stats['totalApplicants']; ?></h3>
                        <p>Total Applicants</p>
                    </div>
                </div>

                <div class="stat-card approved" onclick="filterApplications('approved')">
                    <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                    <div class="stat-info">
                        <h3 id="totalApproved"><?php echo $stats['totalApproved']; ?></h3>
                        <p>Approved</p>
                    </div>
                </div>

                <div class="stat-card denied" onclick="filterApplications('denied')">
                    <div class="stat-icon"><i class="fas fa-times-circle"></i></div>
                    <div class="stat-info">
                        <h3 id="totalDenied"><?php echo $stats['totalDenied']; ?></h3>
                        <p>Denied</p>
                    </div>
                </div>

                <div class="stat-card printed" onclick="filterApplications('printed')">
                    <div class="stat-icon"><i class="fas fa-print"></i></div>
                    <div class="stat-info">
                        <h3 id="totalPrinted"><?php echo $stats['totalPrinted']; ?></h3>
                        <p>Printed</p>
                    </div>
                </div>

                <div class="stat-card collected" onclick="filterApplications('collected')">
                    <div class="stat-icon"><i class="fas fa-hand-holding"></i></div>
                    <div class="stat-info">
                        <h3 id="totalCollected"><?php echo $stats['totalCollected']; ?></h3>
                        <p>Collected</p>
                    </div>
                </div>

                <div class="stat-card overstayed" onclick="filterApplications('overstayed')">
                    <div class="stat-icon"><i class="fas fa-exclamation-triangle"></i></div>
                    <div class="stat-info">
                        <h3 id="totalOverstayed"><?php echo $stats['totalOverstayed']; ?></h3>
                        <p>Overstayed</p>
                    </div>
                </div>
            </div>

            <!-- Main Content Card -->
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
                                    <td>John Banda</td>
                                    <td>john@example.com</td>
                                    <td>Renewal</td>
                                    <td><span class="status-badge status-pending">Pending</span></td>
                                    <td>2025-01-15</td>
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
        // Mobile menu toggle
        const mobileToggle = document.getElementById('mobileToggle');
        const sidebar = document.getElementById('sidebar');
        if (mobileToggle) {
            mobileToggle.addEventListener('click', () => sidebar.classList.toggle('active'));
        }

        // Active menu highlighting
        const currentPage = window.location.pathname.split('/').pop();
        document.querySelectorAll('.nav-link').forEach(link => {
            link.classList.toggle('active', link.getAttribute('href') === currentPage || currentPage === '');
        });

        // Filter buttons
        function filterTable(status) {
            const buttons = document.querySelectorAll('.filter-btn');
            buttons.forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');
            console.log('Filter Table:', status);
        }

        // Filter applications by stat cards
        function filterApplications(type) {
            console.log('Filter by stat card:', type);
        }

        // Search functionality
        const searchInput = document.querySelector('.search-box input');
        searchInput.addEventListener('input', function(e) {
            const term = e.target.value.toLowerCase();
            document.querySelectorAll('#applicantsTable tbody tr').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(term) ? '' : 'none';
            });
        });
    </script>
</body>
</html>
