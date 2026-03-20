CivicEye – Community Reporting System

User registration (name, email, password)

User login & logout

Option for anonymous reporting

2. Report System

Users can create a report with:

Title

Description

Issue type (pothole, crime, hazard, etc.)

Upload images/videos

GPS location (latitude & longitude or manual input)

Timestamps are saved automatically

3. Report Management

Dashboard to view all reports

Show report status: Pending, In Progress, Resolved, Urgent

Update report status

4. Multimedia Support

Upload and store images/videos in a folder (/uploads)

Save file paths in the database

5. Location System

Store GPS coordinates in the database

Display location as text or integrate a simple map (optional)

Project Structure
/civic-eye
  /css
    styles.css
  /js
    scripts.js
  /uploads       # Stores images/videos
  /includes
    header.php
    footer.php
  index.php
  login.php
  register.php
  dashboard.php
  submit_report.php
  config.php     # Database connection
Database Setup (MySQL via XAMPP)
SQL Tables
-- 1. Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. Reports table
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

-- 3. Media table
CREATE TABLE media (
    id INT AUTO_INCREMENT PRIMARY KEY,
    report_id INT,
    file_path VARCHAR(255) NOT NULL,
    file_type VARCHAR(20) NOT NULL,
    FOREIGN KEY (report_id) REFERENCES reports(id)
);

-- 4. Location table
CREATE TABLE location (
    id INT AUTO_INCREMENT PRIMARY KEY,
    report_id INT,
    latitude DECIMAL(10,8),
    longitude DECIMAL(11,8),
    FOREIGN KEY (report_id) REFERENCES reports(id)
);
XAMPP Setup Instructions

Install XAMPP

Download from https://www.apachefriends.org

Install it on your system

Start Apache & MySQL

Open XAMPP Control Panel

Click Start for Apache and MySQL

Open phpMyAdmin

Go to http://localhost/phpmyadmin

Create Database

Click New, name it civic_eye

Run SQL Scripts

Go to the SQL tab in phpMyAdmin

Copy-paste the table creation scripts and execute

Place Project Files

Move your project folder civic-eye into the htdocs folder of XAMPP

Access Project in Browser

Visit http://localhost/civic-eye

PHP Backend

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

CRUD Operations

Create report

Read reports

Update status

Upload files

Validation & Error Handling

Check required fields before submitting

Ensure file types are allowed (images/videos)

Frontend

Clean UI with CSS

Forms for:

Registration (register.php)

Login (login.php)

Report submission (submit_report.php)

Dashboard (dashboard.php) to view reports

JavaScript

Form validation

Optional: dynamic updates without page reload

Data Flow (Simplified)

User → Form: User submits registration/login/report form

Form → PHP: Form data sent to PHP using POST

PHP → Database: PHP validates data, performs queries

Database → PHP: PHP retrieves data for dashboard or status updates

PHP → Frontend: PHP generates HTML to display results

Usage

Open http://localhost/civic-eye in your browser

Register a new user or log in

Submit a report with details and optional media

View all reports in the dashboard

Update report status if you are an admin or assigned user
