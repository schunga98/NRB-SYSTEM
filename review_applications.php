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

// Validate ID
if(!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Invalid application ID.";
    exit();
}

$app_id = (int)$_GET['id'];

// Fetch application with user info
$sql = "SELECT a.*, u.firstname, u.lastname, u.email
        FROM applications a
        JOIN users u ON a.citizen_id = u.id
        WHERE a.id = ?";
$stmt = $db->prepare($sql);
$stmt->execute([$app_id]);
$app = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$app) {
    echo "Application not found.";
    exit();
}

// Handle approve/reject
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $admin_notes = isset($_POST['admin_notes']) ? trim($_POST['admin_notes']) : '';
    
    if($action === 'approve' || $action === 'reject') {
        $status = ($action === 'approve') ? 'approved' : 'rejected';
        $stmt = $db->prepare("UPDATE applications SET status = ?, admin_notes = ?, updated_at = NOW() WHERE id = ?");
        $stmt->execute([$status, $admin_notes, $app_id]);

        // Optional: send email notification
        // mail($app['email'], "Application Status", "Hello ".$app['firstname'].", your application has been ".$status);

        header("Location: verification.php?success={$status}");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Application - <?php echo htmlspecialchars($app['firstname'].' '.$app['lastname']); ?></title>
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
            max-width: 900px;
            margin: 0 auto;
        }

        .header {
            background: white;
            padding: 25px 30px;
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
            font-size: 28px;
            color: #2d3748;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .header h1::before {
            content: '🔍';
            font-size: 32px;
        }

        .btn-back {
            background: linear-gradient(135deg, #718096 0%, #4a5568 100%);
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(113, 128, 150, 0.3);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-back::before {
            content: '←';
            font-size: 18px;
        }

        .btn-back:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(113, 128, 150, 0.4);
        }

        .application-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            animation: fadeIn 0.6s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 30px;
            color: white;
        }

        .card-header h2 {
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .card-header p {
            opacity: 0.9;
            font-size: 15px;
        }

        .card-body {
            padding: 30px;
        }

        .info-section {
            margin-bottom: 30px;
        }

        .info-section h3 {
            font-size: 18px;
            color: #2d3748;
            margin-bottom: 18px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e2e8f0;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .info-item {
            background: #f7fafc;
            padding: 18px;
            border-radius: 10px;
            border-left: 4px solid #667eea;
            transition: all 0.3s ease;
        }

        .info-item:hover {
            background: #edf2f7;
            transform: translateX(5px);
        }

        .info-item .label {
            font-size: 12px;
            color: #718096;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }

        .info-item .value {
            font-size: 16px;
            color: #2d3748;
            font-weight: 600;
        }

        .documents-list {
            list-style: none;
            padding: 0;
        }

        .documents-list li {
            background: #f7fafc;
            padding: 15px 18px;
            border-radius: 8px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.3s ease;
        }

        .documents-list li:hover {
            background: #edf2f7;
            transform: translateX(5px);
        }

        .documents-list li::before {
            content: '📄';
            font-size: 24px;
        }

        .documents-list a {
            color: #4299e1;
            text-decoration: none;
            font-weight: 600;
            flex: 1;
        }

        .documents-list a:hover {
            color: #3182ce;
            text-decoration: underline;
        }

        .no-documents {
            background: #fef5e7;
            padding: 20px;
            border-radius: 8px;
            color: #d68910;
            text-align: center;
            font-weight: 600;
        }

        .notes-section {
            margin-top: 30px;
        }

        .notes-section label {
            display: block;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 10px;
            font-size: 15px;
        }

        .notes-section textarea {
            width: 100%;
            padding: 15px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            resize: vertical;
            min-height: 120px;
            transition: border-color 0.3s ease;
        }

        .notes-section textarea:focus {
            outline: none;
            border-color: #667eea;
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
            padding-top: 30px;
            border-top: 2px solid #e2e8f0;
        }

        .btn {
            padding: 14px 32px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-approve {
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(72, 187, 120, 0.4);
        }

        .btn-approve::before {
            content: '✓';
            font-size: 20px;
        }

        .btn-approve:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(72, 187, 120, 0.5);
        }

        .btn-reject {
            background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(245, 101, 101, 0.4);
        }

        .btn-reject::before {
            content: '✕';
            font-size: 20px;
        }

        .btn-reject:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(245, 101, 101, 0.5);
        }

        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-pending {
            background: linear-gradient(135deg, #fef5e7 0%, #ffeaa7 100%);
            color: #d68910;
        }

        .status-approved {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #065f46;
        }

        .status-rejected {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #991b1b;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            animation: fadeIn 0.3s ease;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: white;
            padding: 35px;
            border-radius: 15px;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.3s ease;
        }

        @keyframes slideUp {
            from {
                transform: translateY(50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal-content h3 {
            margin-bottom: 20px;
            color: #2d3748;
            font-size: 24px;
            text-align: center;
        }

        .modal-content p {
            margin-bottom: 25px;
            color: #4a5568;
            text-align: center;
            font-size: 16px;
            line-height: 1.6;
        }

        .modal-actions {
            display: flex;
            gap: 12px;
            justify-content: center;
        }

        .btn-cancel {
            background: #e2e8f0;
            color: #4a5568;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 15px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-cancel:hover {
            background: #cbd5e0;
        }

        .btn-confirm {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 15px;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
        }

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                gap: 15px;
            }

            .header h1 {
                font-size: 22px;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Review Application</h1>
            <a href="verification.php" class="btn-back">Back to Pending</a>
        </div>

        <div class="application-card">
            <div class="card-header">
                <h2><?php echo htmlspecialchars($app['firstname'].' '.$app['lastname']); ?></h2>
                <p>Application ID: #<?php echo htmlspecialchars($app_id); ?></p>
            </div>

            <div class="card-body">
                <div class="info-section">
                    <h3>📋 Basic Information</h3>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="label">Citizen ID</div>
                            <div class="value"><?php echo htmlspecialchars($app['citizen_id']); ?></div>
                        </div>
                        <div class="info-item">
                            <div class="label">Email Address</div>
                            <div class="value"><?php echo htmlspecialchars($app['email']); ?></div>
                        </div>
                        <div class="info-item">
                            <div class="label">Date Submitted</div>
                            <div class="value"><?php echo date('M d, Y \a\t g:i A', strtotime($app['submitted_at'])); ?></div>
                        </div>
                        <div class="info-item">
                            <div class="label">Current Status</div>
                            <div class="value">
                                <span class="status-badge status-<?php echo $app['status']; ?>">
                                    <?php echo ucfirst($app['status']); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if(!empty($app['reason'])): ?>
                <div class="info-section">
                    <h3>💬 Application Reason</h3>
                    <div class="info-item">
                        <div class="value"><?php echo nl2br(htmlspecialchars($app['reason'])); ?></div>
                    </div>
                </div>
                <?php endif; ?>

                <div class="info-section">
                    <h3>📎 Uploaded Documents</h3>
                    <?php 
                    $docs = !empty($app['documents']) ? explode(',', $app['documents']) : [];
                    if(!empty($docs) && $docs[0] !== ''): ?>
                        <ul class="documents-list">
                            <?php foreach($docs as $doc): ?>
                                <li>
                                    <a href="uploads/<?php echo trim($doc); ?>" target="_blank">
                                        <?php echo htmlspecialchars(trim($doc)); ?>
                                    </a>
                                    <span style="color: #48bb78; font-size: 12px; font-weight: 600;">VIEW</span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <div class="no-documents">⚠️ No documents uploaded with this application</div>
                    <?php endif; ?>
                </div>

                <?php if($app['status'] === 'pending'): ?>
                <form method="POST" id="reviewForm">
                    <div class="notes-section">
                        <label for="admin_notes">📝 Admin Notes (Optional)</label>
                        <textarea name="admin_notes" id="admin_notes" 
                                  placeholder="Add any notes or comments about this application..."></textarea>
                    </div>

                    <div class="action-buttons">
                        <button type="button" class="btn btn-approve" onclick="confirmAction('approve')">
                            Approve Application
                        </button>
                        <button type="button" class="btn btn-reject" onclick="confirmAction('reject')">
                            Reject Application
                        </button>
                    </div>
                    <input type="hidden" name="action" id="actionInput">
                </form>
                <?php else: ?>
                <div class="info-section">
                    <h3>ℹ️ Application Status</h3>
                    <div class="info-item">
                        <div class="value" style="text-align: center; padding: 20px;">
                            This application has already been 
                            <span class="status-badge status-<?php echo $app['status']; ?>">
                                <?php echo ucfirst($app['status']); ?>
                            </span>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div id="confirmModal" class="modal">
        <div class="modal-content">
            <h3 id="modalTitle">Confirm Action</h3>
            <p id="modalMessage">Are you sure you want to proceed?</p>
            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closeModal()">Cancel</button>
                <button type="button" class="btn-confirm" id="confirmBtn" onclick="submitForm()">Confirm</button>
            </div>
        </div>
    </div>

    <script>
        let currentAction = '';

        function confirmAction(action) {
            currentAction = action;
            const modal = document.getElementById('confirmModal');
            const title = document.getElementById('modalTitle');
            const message = document.getElementById('modalMessage');
            const confirmBtn = document.getElementById('confirmBtn');

            if(action === 'approve') {
                title.textContent = '✓ Approve Application';
                message.textContent = 'Are you sure you want to APPROVE this application? The applicant will be notified of the approval.';
                confirmBtn.style.background = 'linear-gradient(135deg, #48bb78 0%, #38a169 100%)';
            } else {
                title.textContent = '✕ Reject Application';
                message.textContent = 'Are you sure you want to REJECT this application? The applicant will be notified of the rejection.';
                confirmBtn.style.background = 'linear-gradient(135deg, #f56565 0%, #e53e3e 100%)';
            }

            modal.classList.add('active');
        }

        function closeModal() {
            document.getElementById('confirmModal').classList.remove('active');
        }

        function submitForm() {
            document.getElementById('actionInput').value = currentAction;
            document.getElementById('reviewForm').submit();
        }

        // Close modal when clicking outside
        document.getElementById('confirmModal').addEventListener('click', function(e) {
            if(e.target === this) {
                closeModal();
            }
        });

        // Close modal on ESC key
        document.addEventListener('keydown', function(e) {
            if(e.key === 'Escape') {
                closeModal();
            }
        });
    </script>
</body>
</html>