<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CivicEye - Community Reporting System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav class="navbar">
        <a href="index.php" class="logo">CivicEye</a>
        <div class="nav-links">
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="dashboard.php">Dashboard</a>
                <a href="submit_report.php" class="btn">Submit Report</a>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="register.php" class="btn">Register</a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="container" style="text-align: center; margin-top: 10vh;">
        <h1 style="font-size: 3.5rem; color: var(--navy); margin-bottom: 1rem; line-height: 1.2;">See it. Report it. Resolve it.</h1>
        <p style="font-size: 1.25rem; color: var(--muted); margin-bottom: 2.5rem; max-width: 650px; margin-left: auto; margin-right: auto;">
            Empowering communities to report local issues, track progress, and improve our neighborhoods together. Be the change your community needs.
        </p>
        <div>
            <a href="register.php" class="btn" style="font-size: 1.25rem; padding: 1rem 2.5rem; margin-right: 1rem;">Get Started</a>
            <a href="dashboard.php" class="btn btn-secondary" style="font-size: 1.25rem; padding: 1rem 2.5rem;">View Reports</a>
        </div>
    </div>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> CivicEye - Community Reporting System</p>
    </footer>
    <script src="js/script.js"></script>
</body>
</html>
