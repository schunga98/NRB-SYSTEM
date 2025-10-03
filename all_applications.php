<?php
include 'includes/database.php'; // your DB connection

$db = new Database();
$conn = $db->getConnection();

// Get all applications
$stmt = $conn->query("SELECT * FROM applications ORDER BY submission_date DESC");
$applicants = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>All Applicants</title>
</head>
<body>
    <h2>All Applicants</h2>
    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th>
            <th>Full Name</th>
            <th>National ID</th>
            <th>Submission Date</th>
            <th>Status</th>
        </tr>
        <?php foreach ($applicants as $app): ?>
        <tr>
            <td><?= $app['id'] ?></td>
            <td><?= htmlspecialchars($app['full_name']) ?></td>
            <td><?= htmlspecialchars($app['national_id']) ?></td>
            <td><?= $app['submission_date'] ?></td>
            <td><?= $app['status'] ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
