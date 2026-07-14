<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'lecturer') {
    header("Location: Login.html");
    exit();
}
include 'db_connect.php';

/** @var PDO $conn */
if (!isset($conn) || !($conn instanceof PDO)) {
    throw new RuntimeException('Database connection is not available.');
}

include 'header.php';

$userId = $_SESSION['user_id'];

// Fetch complaints submitted by this user
$stmt = $conn->prepare("SELECT * FROM complaints WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$userId]);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<link rel="stylesheet" href="css/dashboard.css">

<div class="dashboard-container">
    <div class="welcome-section">
        <h1>Welcome, <?php echo htmlspecialchars($userId); ?>!</h1>
        <p>Role: Lecturer | ICMCS Portal</p>
    </div>

    <div class="dashboard-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2>My Submitted Complaints</h2>
            <a href="Complaint.php" class="btn-primary">File New Complaint</a>
        </div>

        <?php if (count($result) > 0): ?>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Category</th>
                        <th>Location</th>
                        <th>Description</th>
                        <th>Attachment</th>
                        <th>Status</th>
                        <th>Submitted At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($result as $row): ?>
                        <tr>
                            <td>#<?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['category']); ?></td>
                            <td><?php echo htmlspecialchars($row['location']); ?></td>
                            <td><?php echo htmlspecialchars($row['description']); ?></td>
                            <td>
                                <?php if ($row['image']): ?>
                                    <a href="<?php echo htmlspecialchars($row['image']); ?>" target="_blank">View Image</a>
                                <?php else: ?>
                                    None
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge <?php echo strtolower(str_replace(' ', '-', $row['status'])); ?>">
                                    <?php echo htmlspecialchars($row['status']); ?>
                                </span>
                            </td>
                            <td><?php echo $row['created_at']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="text-align: center; color: #777; padding: 20px 0;">You have not submitted any complaints yet.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
