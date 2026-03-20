<?php
session_start();
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

// Explain: dashboard fetches data by joining the reports, media, and location tables
$query = "
    SELECT r.id, r.title, r.description, r.issue_type, r.status, r.created_at, 
           m.file_path, l.latitude, l.longitude
    FROM reports r
    LEFT JOIN media m ON r.id = m.report_id
    LEFT JOIN location l ON r.id = l.report_id
    ORDER BY r.created_at DESC
";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - CivicEye</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav class="navbar">
        <a href="index.php" class="logo">CivicEye</a>
        <div class="nav-links">
            <a href="dashboard.php" style="color: var(--civic-orange);">Dashboard</a>
            <a href="submit_report.php" class="btn">Submit Report</a>
            <a href="logout.php">Logout</a>
        </div>
    </nav>

    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h2>Community Reports</h2>
            <p>Welcome, <strong><?php echo htmlspecialchars($user_name); ?></strong></p>
        </div>

        <div class="reports-grid">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="card">
                        <?php if ($row['file_path']): ?>
                            <?php 
                                $ext = strtolower(pathinfo($row['file_path'], PATHINFO_EXTENSION));
                                if (in_array($ext, ['mp4', 'avi'])): 
                            ?>
                                <video src="<?php echo htmlspecialchars($row['file_path']); ?>" controls class="report-image"></video>
                            <?php else: ?>
                                <img src="<?php echo htmlspecialchars($row['file_path']); ?>" alt="Report Media" class="report-image">
                            <?php endif; ?>
                        <?php endif; ?>
                        
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                            <h3 class="card-title"><?php echo htmlspecialchars($row['title']); ?></h3>
                            <?php 
                                $status_class = strtolower(str_replace(' ', '-', $row['status']));
                            ?>
                            <span class="badge badge-<?php echo $status_class; ?>"><?php echo htmlspecialchars($row['status']); ?></span>
                        </div>
                        
                        <div class="card-meta">
                            <span>Type: <?php echo htmlspecialchars($row['issue_type']); ?></span> | 
                            <span>Date: <?php echo date('M d, Y', strtotime($row['created_at'])); ?></span>
                        </div>
                        
                        <p style="margin-bottom: 1rem; color: var(--steel);">
                            <?php echo nl2br(htmlspecialchars($row['description'])); ?>
                        </p>
                        
                        <?php if ($row['latitude'] && $row['longitude']): ?>
                            <div class="card-meta" style="margin-bottom: 0;">
                                📍 Location: <?php echo $row['latitude'] . ', ' . $row['longitude']; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="card" style="grid-column: 1 / -1; text-align: center; padding: 3rem;">
                    <h3 style="color: var(--muted); margin-bottom: 1rem;">No reports yet.</h3>
                    <p style="margin-bottom: 1.5rem;">Be the first to report an issue in your community.</p>
                    <a href="submit_report.php" class="btn">Submit a Report</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
