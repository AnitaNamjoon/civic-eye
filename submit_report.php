<?php
session_start();
require_once 'config.php';

// User must be logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $title = sanitize_input($_POST['title']);
    $description = sanitize_input($_POST['description']);
    $issue_type = sanitize_input($_POST['issue_type']);
    $latitude = sanitize_input($_POST['latitude']);
    $longitude = sanitize_input($_POST['longitude']);
    $status = 'Pending'; // Default status
    
    // Begin database transaction for multiple inserts (reports, media, location)
    $conn->begin_transaction();
    
    try {
        // 1. Insert into reports table
        $stmt = $conn->prepare("INSERT INTO reports (user_id, title, description, issue_type, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $user_id, $title, $description, $issue_type, $status);
        $stmt->execute();
        $report_id = $conn->insert_id;
        $stmt->close();
        
        // 2. Handle File Upload
        // Explain: How file uploads work - we check if a file was uploaded, validate type, move to /uploads, and insert path to db
        if (isset($_FILES['report_file']) && $_FILES['report_file']['error'] == 0) {
            $allowed_types = ['image/jpeg', 'image/png', 'video/mp4', 'video/avi'];
            $file_type = $_FILES['report_file']['type'];
            
            if (in_array($file_type, $allowed_types)) {
                $file_name = time() . '_' . basename($_FILES['report_file']['name']);
                $target_dir = 'uploads/';
                $target_path = $target_dir . $file_name;
                
                // Move uploaded file to uploads folder
                if (move_uploaded_file($_FILES['report_file']['tmp_name'], $target_path)) {
                    // Save in media table
                    $media_stmt = $conn->prepare("INSERT INTO media (report_id, file_path, file_type) VALUES (?, ?, ?)");
                    $media_stmt->bind_param("iss", $report_id, $target_path, $file_type);
                    $media_stmt->execute();
                    $media_stmt->close();
                } else {
                    throw new Exception("Error uploading file.");
                }
            } else {
                throw new Exception("Invalid file type. Only JPG, PNG, MP4, and AVI are allowed.");
            }
        }
        
        // 3. Save location
        if (!empty($latitude) && !empty($longitude)) {
            $loc_stmt = $conn->prepare("INSERT INTO location (report_id, latitude, longitude) VALUES (?, ?, ?)");
            $loc_stmt->bind_param("idd", $report_id, $latitude, $longitude);
            $loc_stmt->execute();
            $loc_stmt->close();
        }
        
        // Commit transaction if all queries succeed
        $conn->commit();
        $success = "Report submitted successfully!";
        
    } catch (Exception $e) {
        $conn->rollback();
        $error = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Report - CivicEye</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav class="navbar">
        <a href="index.php" class="logo">CivicEye</a>
        <div class="nav-links">
            <a href="dashboard.php">Dashboard</a>
            <a href="submit_report.php" class="btn">Submit Report</a>
            <a href="logout.php">Logout</a>
        </div>
    </nav>

    <div class="container">
        <div class="card" style="max-width: 800px; margin: 0 auto;">
            <h2 style="margin-bottom: 1.5rem;">Submit a Report</h2>
            
            <?php if(!empty($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if(!empty($success)): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
                <div style="margin-bottom: 1.5rem; text-align: center;">
                    <a href="dashboard.php" class="btn">Go to Dashboard</a>
                </div>
            <?php else: ?>
                <form action="submit_report.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="title">Issue Title</label>
                        <input type="text" id="title" name="title" required placeholder="E.g., Pothole on Main St">
                    </div>
                    
                    <div class="form-group">
                        <label for="issue_type">Issue Type</label>
                        <select id="issue_type" name="issue_type" required>
                            <option value="">Select a category</option>
                            <option value="Infrastructure">Infrastructure (Potholes, Sidewalks)</option>
                            <option value="Sanitation">Sanitation (Garbage, Illegal Dumping)</option>
                            <option value="Safety">Public Safety (Streetlights, Hazards)</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Detailed Description</label>
                        <textarea id="description" name="description" required placeholder="Describe the problem, when you noticed it, etc."></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="report_file">Upload Photo/Video</label>
                        <!-- Explain: How forms send data with files (enctype="multipart/form-data") -->
                        <input type="file" id="report_file" name="report_file" accept=".jpg,.jpeg,.png,.mp4,.avi">
                        <div class="form-text">Allowed: JPG, PNG, MP4, AVI</div>
                        <div id="image_preview"></div>
                    </div>
                    
                    <div class="form-group">
                        <label>Location</label>
                        <div style="display: flex; gap: 1rem; margin-bottom: 0.5rem;">
                            <input type="text" id="latitude" name="latitude" placeholder="Latitude" readonly style="background-color: var(--snow);">
                            <input type="text" id="longitude" name="longitude" placeholder="Longitude" readonly style="background-color: var(--snow);">
                        </div>
                        <button type="button" id="get_location_btn" class="btn btn-secondary" style="width: auto;">Get My Location</button>
                    </div>
                    
                    <button type="submit" class="btn" style="width: 100%; font-size: 1.125rem; padding: 1rem;">Submit Report</button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <script src="js/script.js"></script>
</body>
</html>
