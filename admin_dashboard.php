<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: Login.html");
    exit();
}
include 'db_connect.php';

/** @var PDO $conn */
if (!isset($conn) || !($conn instanceof PDO)) {
    throw new RuntimeException('Database connection is not available.');
}

include 'header.php';

// Fetch complaint status counts
$solved_count = (int) $conn->query("SELECT COUNT(*) FROM complaints WHERE status = 'Solved'")->fetchColumn();
$in_progress_count = (int) $conn->query("SELECT COUNT(*) FROM complaints WHERE status = 'In Progress'")->fetchColumn();
$unsolved_count = (int) $conn->query("SELECT COUNT(*) FROM complaints WHERE status = 'Unsolved'")->fetchColumn();

// Fetch user traffic / registered count by role
$user_traffic = $conn->query("SELECT role, COUNT(*) as count FROM users GROUP BY role")->fetchAll(PDO::FETCH_ASSOC);
$users_by_role = [];
$total_users = 0;
foreach ($user_traffic as $r) {
    $users_by_role[$r['role']] = $r['count'];
    $total_users += $r['count'];
}

// Fetch complaint history list
$complaints_res = $conn->query("SELECT * FROM complaints ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<link rel="stylesheet" href="css/dashboard.css">

<div class="dashboard-container">
    <div class="welcome-section">
        <h1>Welcome, Admin <?php echo htmlspecialchars($_SESSION['user_id']); ?>!</h1>
        <p>Role: Administrator | ICMCS Portal</p>
    </div>

    <!-- Stats Grid mimicking the counts -->
    <div class="stats-grid">
        <div class="stat-card solved">
            <h3>Solved Complaints</h3>
            <div class="value" id="solvedCountVal"><?php echo $solved_count; ?></div>
        </div>
        <div class="stat-card in-progress">
            <h3>In Progress</h3>
            <div class="value" id="inProgressCountVal"><?php echo $in_progress_count; ?></div>
        </div>
        <div class="stat-card unsolved">
            <h3>Unsolved Complaints</h3>
            <div class="value" id="unsolvedCountVal"><?php echo $unsolved_count; ?></div>
        </div>
        <div class="stat-card users">
            <h3>Total Registered Users</h3>
            <div class="value"><?php echo $total_users; ?></div>
        </div>
    </div>

    <!-- Admin dashboard structure as defined in html/Admin.html -->
    <div class="dashboard-card">
        <h2>Admin Panel</h2>
        
        <!-- Complaint Status list -->
        <div class="complaints-status" style="margin-bottom: 30px;">
            <h3>Complaint Status Summary</h3>
            <ul>
                <li>Solved: <span id="solvedCount" style="font-weight: bold; color: #28a745;"><?php echo $solved_count; ?></span></li>
                <li>In Progress: <span id="inProgressCount" style="font-weight: bold; color: #ffc107;"><?php echo $in_progress_count; ?></span></li>
                <li>Unsolved: <span id="unsolvedCount" style="font-weight: bold; color: #dc3545;"><?php echo $unsolved_count; ?></span></li>
            </ul>
        </div>

        <!-- User Traffic Monitoring -->
        <div class="user-traffic" style="margin-bottom: 30px;">
            <h3>User Traffic Monitoring (Registered Users by Role)</h3>
            <div id="userTrafficData">
                <table class="data-table" style="max-width: 500px;">
                    <thead>
                        <tr>
                            <th>User Role</th>
                            <th>Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Student</td>
                            <td><?php echo $users_by_role['student'] ?? 0; ?></td>
                        </tr>
                        <tr>
                            <td>Lecturer</td>
                            <td><?php echo $users_by_role['lecturer'] ?? 0; ?></td>
                        </tr>
                        <tr>
                            <td>Technician</td>
                            <td><?php echo $users_by_role['technician'] ?? 0; ?></td>
                        </tr>
                        <tr>
                            <td>Admin</td>
                            <td><?php echo $users_by_role['admin'] ?? 0; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Complaint History -->
        <div class="complaint-history" style="margin-bottom: 30px;">
            <h3>Complaint History List</h3>
            <div id="complaintHistoryList">
                <?php if (count($complaints_res) > 0): ?>
                    <table class="data-table" id="complaintsTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Submitted By</th>
                                <th>Category</th>
                                <th>Location</th>
                                <th>Description</th>
                                <th>Attachment</th>
                                <th>Submitted At</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($complaints_res as $row): ?>
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
                                        <form action="update_status.php" method="POST" style="margin: 0;">
                                            <input type="hidden" name="complaint_id" value="<?php echo $row['id']; ?>">
                                            <select name="status" class="status-select" onchange="this.form.submit()">
                                                <option value="Unsolved" <?php echo $row['status'] === 'Unsolved' ? 'selected' : ''; ?>>Unsolved</option>
                                                <option value="In Progress" <?php echo $row['status'] === 'In Progress' ? 'selected' : ''; ?>>In Progress</option>
                                                <option value="Solved" <?php echo $row['status'] === 'Solved' ? 'selected' : ''; ?>>Solved</option>
                                            </select>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p style="color: #777;">No complaints submitted yet.</p>
                <?php endif; ?>
            </div>
        </div>

        <button onclick="generateReport()" class="btn-primary">Generate Report</button>
    </div>
</div>

<script>
function generateReport() {
    window.print();
}
</script>

</body>
</html>
