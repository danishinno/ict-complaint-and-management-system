<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'technician') {
    header("Location: Login.html");
    exit();
}
include 'db_connect.php';
include 'header.php';

// Fetch all complaints
$result = $conn->query("SELECT * FROM complaints ORDER BY created_at DESC");
?>

<link rel="stylesheet" href="css/dashboard.css">

<div class="dashboard-container">
    <div class="welcome-section">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['user_id']); ?>!</h1>
        <p>Role: Technician | ICMCS Portal</p>
    </div>

    <div class="dashboard-card">
        <h2>Manage Complaints</h2>
        
        <?php if (isset($_GET['status']) && $_GET['status'] === 'updated'): ?>
            <div class="status-message success" style="margin-bottom: 20px; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; padding: 12px; border-radius: 4px; text-align: center; font-weight: bold;">
                Complaint status updated successfully!
            </div>
        <?php endif; ?>

        <?php if ($result && $result->num_rows > 0): ?>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User ID</th>
                        <th>Category</th>
                        <th>Location</th>
                        <th>Description</th>
                        <th>Attachment</th>
                        <th>Submitted At</th>
                        <th>Current Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td>#<?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['user_id'] ?? 'Guest'); ?></td>
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
                            <td><?php echo $row['created_at']; ?></td>
                            <td>
                                <span class="badge <?php echo strtolower(str_replace(' ', '-', $row['status'])); ?>">
                                    <?php echo htmlspecialchars($row['status']); ?>
                                </span>
                            </td>
                            <td>
                                <form action="update_status.php" method="POST" style="margin: 0; display: inline-flex; gap: 5px;">
                                    <input type="hidden" name="complaint_id" value="<?php echo $row['id']; ?>">
                                    <select name="status" class="status-select" onchange="this.form.submit()">
                                        <option value="Unsolved" <?php echo $row['status'] === 'Unsolved' ? 'selected' : ''; ?>>Unsolved</option>
                                        <option value="In Progress" <?php echo $row['status'] === 'In Progress' ? 'selected' : ''; ?>>In Progress</option>
                                        <option value="Solved" <?php echo $row['status'] === 'Solved' ? 'selected' : ''; ?>>Solved</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="text-align: center; color: #777; padding: 20px 0;">No complaints found in the database.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
