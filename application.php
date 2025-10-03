<?php
session_start();
require_once 'includes/database.php';

// Check if admin is logged in
// Uncomment the below lines if you have admin login
// if (!isset($_SESSION['admin_id'])) {
//     header('Location: login.php');
//     exit();
// }

// Initialize database connection
$database = new Database();
$db = $database->getConnection();

// Handle status updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'update_status' && isset($_POST['application_id'], $_POST['status'])) {
        $stmt = $db->prepare("UPDATE applications SET status = ?, updated_at = NOW() WHERE id = ?");
        $stmt->execute([$_POST['status'], $_POST['application_id']]);
        header('Location: applicants.php?success=status_updated');
        exit();
    } elseif ($_POST['action'] === 'delete' && isset($_POST['application_id'])) {
        $stmt = $db->prepare("DELETE FROM applications WHERE id = ?");
        $stmt->execute([$_POST['application_id']]);
        header('Location: applicants.php?success=deleted');
        exit();
    }
}

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$records_per_page = 10;
$offset = ($page - 1) * $records_per_page;

// Search and filter
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';

// Build WHERE clauses
$where_clauses = [];
$params = [];

if (!empty($search)) {
    $where_clauses[] = "(firstname LIKE ? OR lastname LIKE ? OR email LIKE ? OR reference_number LIKE ?)";
    $search_param = "%$search%";
    $params = array_merge($params, [$search_param, $search_param, $search_param, $search_param]);
}

if (!empty($status_filter)) {
    $where_clauses[] = "status = ?";
    $params[] = $status_filter;
}

$where_sql = !empty($where_clauses) ? "WHERE " . implode(" AND ", $where_clauses) : "";

// Count total records for pagination
$count_sql = "SELECT COUNT(*) as total FROM applications $where_sql";
$count_stmt = $db->prepare($count_sql);
$count_stmt->execute($params);
$total_records = $count_stmt->fetch(PDO::FETCH_ASSOC)['total'];
$total_pages = ceil($total_records / $records_per_page);

// Fetch applicants
$records_per_page = (int)$records_per_page;
$offset = (int)$offset;

$sql = "SELECT id, firstname, lastname, email, reference_number, status, created_at, updated_at
        FROM applications
        $where_sql
        ORDER BY created_at DESC
        LIMIT $records_per_page OFFSET $offset";

$stmt = $db->prepare($sql);
$stmt->execute($params);
$applicants = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Applicants - Admin Panel</title>
    <style>
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; background: #f5f7fa; color: #2d3748; line-height: 1.6; }
        .container { max-width: 1400px; margin: 0 auto; padding: 20px; }
        .header { background: white; padding: 25px 30px; border-radius: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); margin-bottom: 25px; display: flex; justify-content: space-between; align-items: center; }
        .header h1 { font-size: 28px; color: #1a202c; }
        .header .back-btn { background: #9c1010ff; color: white; padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; text-decoration: none; font-size: 14px; transition: background 0.2s; }
        .header .back-btn:hover { background: #c01010ff; }
        .filters { background: white; padding: 20px 30px; border-radius: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); margin-bottom: 25px; }
        .filters form { display: flex; gap: 15px; align-items: end; flex-wrap: wrap; }
        .filter-group { flex: 1; min-width: 200px; }
        .filter-group label { display: block; margin-bottom: 8px; font-weight: 600; font-size: 14px; color: #4a5568; }
        .filter-group input, .filter-group select { width: 100%; padding: 10px 15px; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 14px; transition: border-color 0.2s; }
        .filter-group input:focus, .filter-group select:focus { outline: none; border-color: #4299e1; }
        .filter-btn { background: #4299e1; color: white; padding: 10px 25px; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 600; transition: background 0.2s; }
        .filter-btn:hover { background: #3182ce; }
        .reset-btn { background: #718096; color: white; padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; text-decoration: none; display: inline-block; transition: background 0.2s; }
        .reset-btn:hover { background: #4a5568; }
        .alert { padding: 15px 20px; border-radius: 6px; margin-bottom: 25px; font-size: 14px; }
        .alert-success { background: #c6f6d5; color: #22543d; border: 1px solid #9ae6b4; }
        .table-container { background: white; border-radius: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); overflow: hidden; }
        .stats { padding: 20px 30px; border-bottom: 1px solid #e2e8f0; }
        .stats p { font-size: 14px; color: #718096; }
        .stats strong { color: #2d3748; }
        table { width: 100%; border-collapse: collapse; }
        thead { background: #f7fafc; }
        th { padding: 15px 20px; text-align: left; font-weight: 600; font-size: 13px; color: #4a5568; text-transform: uppercase; letter-spacing: 0.5px; }
        td { padding: 18px 20px; border-top: 1px solid #e2e8f0; font-size: 14px; }
        tbody tr:hover { background: #f7fafc; }
        .status-badge { display: inline-block; padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; text-transform: capitalize; }
        .status-pending { background: #fef5e7; color: #d68910; }
        .status-reviewed { background: #dbeafe; color: #1e40af; }
        .status-approved { background: #d1fae5; color: #065f46; }
        .status-rejected { background: #fee2e2; color: #991b1b; }
        .actions { display: flex; gap: 8px; }
        .btn { padding: 6px 12px; border: none; border-radius: 4px; cursor: pointer; font-size: 12px; font-weight: 600; text-decoration: none; display: inline-block; transition: all 0.2s; }
        .btn-view { background: #4299e1; color: white; }
        .btn-view:hover { background: #3182ce; }
        .btn-edit { background: #48bb78; color: white; }
        .btn-edit:hover { background: #38a169; }
        .btn-delete { background: #f56565; color: white; }
        .btn-delete:hover { background: #e53e3e; }
        .pagination { padding: 20px 30px; display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #e2e8f0; }
        .pagination-info { font-size: 14px; color: #718096; }
        .pagination-links { display: flex; gap: 8px; }
        .pagination-links a, .pagination-links span { padding: 8px 14px; border-radius: 6px; font-size: 14px; text-decoration: none; color: #4a5568; background: #f7fafc; transition: all 0.2s; }
        .pagination-links a:hover { background: #4299e1; color: white; }
        .pagination-links .active { background: #4299e1; color: white; }
        .no-data { padding: 60px 20px; text-align: center; color: #718096; }
        .no-data p { font-size: 16px; margin-bottom: 10px; }
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; }
        .modal.active { display: flex; }
        .modal-content { background: white; padding: 30px; border-radius: 10px; max-width: 500px; width: 90%; }
        .modal-content h3 { margin-bottom: 20px; color: #1a202c; }
        .modal-content select { width: 100%; padding: 10px; border: 1px solid #e2e8f0; border-radius: 6px; margin-bottom: 20px; font-size: 14px; }
        .modal-actions { display: flex; gap: 10px; justify-content: flex-end; }
        .btn-cancel { background: #e2e8f0; color: #4a5568; padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; }
        .btn-submit { background: #48bb78; color: white; padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>All Applicants</h1>
            <a href="admin_page.php" class="logout-btn">Back</a>
        </div>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                <?php
                if ($_GET['success'] === 'status_updated') {
                    echo '✓ Application status updated successfully!';
                } elseif ($_GET['success'] === 'deleted') {
                    echo '✓ Application deleted successfully!';
                }
                ?>
            </div>
        <?php endif; ?>

        <div class="filters">
            <form method="GET" action="applicants.php">
                <div class="filter-group">
                    <label>Search</label>
                    <input type="text" name="search" placeholder="Name, email, or reference number" 
                           value="<?php echo htmlspecialchars($search); ?>">
                </div>
                <div class="filter-group">
                    <label>Status</label>
                    <select name="status">
                        <option value="">All Statuses</option>
                        <option value="pending" <?php echo $status_filter === 'pending' ? 'selected' : ''; ?>>Pending</option>
                        <option value="reviewed" <?php echo $status_filter === 'reviewed' ? 'selected' : ''; ?>>Reviewed</option>
                        <option value="approved" <?php echo $status_filter === 'approved' ? 'selected' : ''; ?>>Approved</option>
                        <option value="rejected" <?php echo $status_filter === 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                    </select>
                </div>
                <button type="submit" class="filter-btn">Filter</button>
                <a href="applicants.php" class="reset-btn">Reset</a>
            </form>
        </div>

        <div class="table-container">
            <div class="stats">
                <p>Showing <strong><?php echo count($applicants); ?></strong> of <strong><?php echo $total_records; ?></strong> total applicants</p>
            </div>

            <?php if (count($applicants) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Full Name</th>
                            <th>Reference Number</th>
                            <th>Email Address</th>
                            <th>Status</th>
                            <th>Date Submitted</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($applicants as $applicant): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($applicant['firstname'] . ' ' . $applicant['lastname']); ?></td>
                                <td><strong><?php echo htmlspecialchars($applicant['reference_number']); ?></strong></td>
                                <td><?php echo htmlspecialchars($applicant['email']); ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo $applicant['status']; ?>">
                                        <?php echo ucfirst($applicant['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('M d, Y', strtotime($applicant['created_at'])); ?></td>
                                <td>
                                    <div class="actions">
                                        <a href="view_application.php?id=<?php echo $applicant['id']; ?>" class="btn btn-view">View</a>
                                        <button onclick="openStatusModal(<?php echo $applicant['id']; ?>, '<?php echo $applicant['status']; ?>')" 
                                                class="btn btn-edit">Update</button>
                                        <button onclick="deleteApplication(<?php echo $applicant['id']; ?>)" 
                                                class="btn btn-delete">Delete</button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <?php if ($total_pages > 1): ?>
                    <div class="pagination">
                        <div class="pagination-info">
                            Page <?php echo $page; ?> of <?php echo $total_pages; ?>
                        </div>
                        <div class="pagination-links">
                            <?php if ($page > 1): ?>
                                <a href="?page=<?php echo $page - 1; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?><?php echo $status_filter ? '&status=' . $status_filter : ''; ?>">Previous</a>
                            <?php endif; ?>

                            <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                                <a href="?page=<?php echo $i; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?><?php echo $status_filter ? '&status=' . $status_filter : ''; ?>" 
                                   class="<?php echo $i === $page ? 'active' : ''; ?>">
                                    <?php echo $i; ?>
                                </a>
                            <?php endfor; ?>

                            <?php if ($page < $total_pages): ?>
                                <a href="?page=<?php echo $page + 1; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?><?php echo $status_filter ? '&status=' . $status_filter : ''; ?>">Next</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="no-data">
                    <p><strong>No applicants found</strong></p>
                    <p>Try adjusting your search or filter criteria.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Status Update Modal -->
    <div id="statusModal" class="modal">
        <div class="modal-content">
            <h3>Update Application Status</h3>
            <form id="statusForm" method="POST" action="applicants.php">
                <input type="hidden" name="action" value="update_status">
                <input type="hidden" name="application_id" id="modalApplicationId">
                <select name="status" id="modalStatus" required>
                    <option value="pending">Pending</option>
                    <option value="reviewed">Reviewed</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                </select>
                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="closeStatusModal()">Cancel</button>
                    <button type="submit" class="btn-submit">Update Status</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Form -->
    <form id="deleteForm" method="POST" action="applicants.php" style="display: none;">
        <input type="hidden" name="action" value="delete">
        <input type="hidden" name="application_id" id="deleteApplicationId">
    </form>

    <script>
        function openStatusModal(id, currentStatus) {
            document.getElementById('modalApplicationId').value = id;
            document.getElementById('modalStatus').value = currentStatus;
            document.getElementById('statusModal').classList.add('active');
        }

        function closeStatusModal() {
            document.getElementById('statusModal').classList.remove('active');
        }

        function deleteApplication(id) {
            if (confirm('Are you sure you want to delete this application? This action cannot be undone.')) {
                document.getElementById('deleteApplicationId').value = id;
                document.getElementById('deleteForm').submit();
            }
        }

        // Close modal when clicking outside
        document.getElementById('statusModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeStatusModal();
            }
        });
    </script>
</body>
</html>
