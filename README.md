🕵️‍♀️ CivicEye – Community Reporting System

Register with name, email & password

Login & logout

Option for anonymous reporting

📝 Report System

Submit reports with:

Title

Description

Issue type (pothole, crime, hazard, etc.)

Upload images/videos 📷

GPS location 🌍 (latitude/longitude or manual input)

Timestamp is saved automatically 🕒

📊 Report Management

Dashboard to view all reports

Report status: Pending, In Progress, Resolved, Urgent

Update report status

🖼 Multimedia Support

Upload images/videos in /uploads

Store file paths in database

📍 Location System

Store GPS coordinates in database

Optional: display a simple map 🗺

🗂 Project Structure
/civic-eye
  /css
    styles.css       # Styles for UI
  /js
    scripts.js       # JavaScript for forms & validation
  /uploads           # Store images/videos here
  /includes
    header.php
    footer.php
  index.php
  login.php
  register.php
  dashboard.php
  submit_report.php
  config.php         # Database connection
🗃 Database Setup (MySQL via XAMPP)
SQL Tables
-- Users Table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Reports Table
CREATE TABLE reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    issue_type VARCHAR(50) NOT NULL,
    status VARCHAR(50) DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Media Table
CREATE TABLE media (
    id INT AUTO_INCREMENT PRIMARY KEY,
    report_id INT,
    file_path VARCHAR(255) NOT NULL,
    file_type VARCHAR(20) NOT NULL,
    FOREIGN KEY (report_id) REFERENCES reports(id)
);

-- Location Table
CREATE TABLE location (
    id INT AUTO_INCREMENT PRIMARY KEY,
    report_id INT,
    latitude DECIMAL(10,8),
    longitude DECIMAL(11,8),
    FOREIGN KEY (report_id) REFERENCES reports(id)
);
⚡ XAMPP Setup Instructions

Install XAMPP 🛠

Download from https://www.apachefriends.org

Follow installation steps

Start Apache & MySQL ▶

Open XAMPP Control Panel

Click Start for Apache & MySQL

Open phpMyAdmin 💻

Go to http://localhost/phpmyadmin

Create Database 🗄

Click New, name it civic_eye

Run SQL Scripts ⌨️

Copy & paste table scripts above into SQL tab and execute

Place Project Files 📂

Move civic-eye folder to htdocs

Access Project in Browser 🌐

Open http://localhost/civic-eye

🛠 PHP Backend

Database Connection (config.php)

<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "civic_eye";

$conn = new mysqli($host, $user, $pass, $db);

if($conn->connect_error){
    die("Connection failed: " . $conn->connect_error);
}
?>

CRUD Operations:

Create report 📝

Read reports 📊

Update status 🔄

Upload files 📂

Validation & Error Handling:

Ensure all required fields are filled

Validate file types (images/videos)

🎨 Frontend

Clean UI with CSS

Forms for:

Registration 📝

Login 🔑

Report Submission 📝

Dashboard to view all reports 📊

JavaScript for:

Form validation ✅

Optional: dynamic updates without reload 🔄

🔄 Data Flow (Simplified)

User → Form 📝: User submits form

Form → PHP 💻: Data sent using POST

PHP → Database 🗄: Data stored in MySQL

Database → PHP 💻: PHP retrieves data

PHP → Frontend 🌐: Display results in dashboard

🚀 Usage

Open http://localhost/civic-eye in your browser

Register or login

Submit a report with details & optional media 📷

View reports in the dashboard

Update report status (if authorized) 🔄
