<?php
session_start();
require_once 'includes/database.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

$database = new Database();
$db = $database->getConnection();

// Fetch pending applications with user info
$sql = "SELECT a.id, u.name, a.citizen_id, a.submitted_at, a.admin_notes
        FROM applications a
        JOIN users u ON a.citizen_id = u.id
        WHERE a.status = 'pending'
        ORDER BY a.submitted_at DESC";

$stmt = $db->prepare($sql);
$stmt->execute();
$pending_apps = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Optional success message
$success_msg = '';
if(isset($_GET['success'])) {
    $allowed = ['approved', 'rejected'];
    if(in_array($_GET['success'], $allowed)) {
        $success_msg = "Application successfully ".$_GET['success'].".";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Applications - Admin Panel</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            animation: slideDown 0.5s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .header h1 {
            font-size: 32px;
            color: #2d3748;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .header h1::before {
            content: '📋';
            font-size: 36px;
        }

        .logout-btn {
            background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%);
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            font-size: 15px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(245, 101, 101, 0.3);
        }

        .logout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(245, 101, 101, 0.4);
        }

        .alert {
            padding: 16px 24px;
            border-radius: 10px;
            margin-bottom: 25px;
            font-size: 15px;
            font-weight: 500;
            animation: fadeIn 0.5s ease;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .alert-success {
            background: linear-gradient(135deg, #c6f6d5 0%, #9ae6b4 100%);
            color: #22543d;
            border-left: 4px solid #38a169;
        }

        .alert-success::before {
            content: '✓';
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            background: #38a169;
            color: white;
            border-radius: 50%;
            font-weight: bold;
        }

        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
            animation: slideUp 0.6s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .stat-card {
            background: white;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .stat-card .number {
            font-size: 36px;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 8px;
        }

        .stat-card .label {
            font-size: 14px;
            color: #718096;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            animation: fadeIn 0.7s ease;
        }

        .table-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 24px 30px;
            color: white;
        }

        .table-header h2 {
            font-size: 22px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .table-header h2::before {
            content: '⏳';
            font-size: 24px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: #f7fafc;
        }

        th {
            padding: 18px 24px;
            text-align: left;
            font-weight: 600;
            font-size: 13px;
            color: #4a5568;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e2e8f0;
        }

        td {
            padding: 20px 24px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 15px;
            color: #2d3748;
        }

        tbody tr {
            transition: all 0.2s ease;
        }

        tbody tr:hover {
            background: linear-gradient(90deg, #f7fafc 0%, #edf2f7 100%);
            transform: scale(1.01);
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            text-align: center;
        }

        .btn-view {
            background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(66, 153, 225, 0.3);
        }

        .btn-view:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(66, 153, 225, 0.4);
        }

        .no-data {
            padding: 80px 20px;
            text-align: center;
        }

        .no-data-icon {
            font-size: 80px;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        .no-data p {
            font-size: 18px;
            color: #718096;
            margin-bottom: 8px;
        }

        .no-data p:first-of-type {
            font-size: 22px;
            font-weight: 600;
            color: #2d3748;
        }

        .badge {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            background: linear-gradient(135deg, #fef5e7 0%, #ffeaa7 100%);
            color: #d68910;
            border: 1px solid #f9ca24;
        }

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                gap: 20px;
                text-align: center;
            }

            .header h1 {
                font-size: 24px;
            }

            .table-container {
                overflow-x: auto;
            }

            table {
                min-width: 600px;
            }

            th, td {
                padding: 12px 16px;
                font-size: 13px;
            }
        }

        .loading {
            text-align: center;
            padding: 40px;
            color: #718096;
        }

        .loading::after {
            content: '⏳';
            font-size: 48px;
            display: block;
            margin-top: 20px;
            animation: rotate 2s linear infinite;
        }

        @keyframes rotate {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Verify Applications</h1>
            <a href="admin_page.php" class="logout-btn">Logout</a>
        </div>

        <?php if($success_msg): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success_msg); ?></div>
        <?php endif; ?>

        <div class="stats-container">
            <div class="stat-card">
                <div class="number"><?php echo count($pending_apps); ?></div>
                <div class="label">Pending Applications</div>
            </div>
        </div>

        <div class="table-container">
            <div class="table-header">
                <h2>Applications Awaiting Review</h2>
            </div>

            <?php if(count($pending_apps) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Full Name</th>
                            <th>Citizen ID</th>
                            <th>Date Submitted</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($pending_apps as $app): ?>
                            <tr>
                                <td>
                                    <strong><?php echo htmlspecialchars($app['name']); ?></strong>
                                </td>
                                <td><?php echo htmlspecialchars($app['citizen_id']); ?></td>
                                <td><?php echo date('M d, Y \a\t g:i A', strtotime($app['submitted_at'])); ?></td>
                                <td>
                                    <span class="badge">Pending Review</span>
                                </td>
                                <td>
                                    <a href="review_application.php?id=<?php echo $app['id']; ?>" 
                                       class="btn btn-view">
                                        View & Review
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-data">
                    <div class="no-data-icon">✅</div>
                    <p>All Clear!</p>
                    <p>No pending applications found at the moment.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>