<?php
// Database connection
$DB_HOST = 'localhost'; // Change if using a different host
$DB_USER = 'root'; // Default user for XAMPP
$DB_PASS = ''; // Default password for XAMPP (empty)
$DB_NAME = 'campus_recruitment'; // Change to your database name

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $companyname = $_POST['company'];
    $jobrole = $_POST['jobrole'];
    $mincgpa = $_POST['cgpa'];
    $backloglimit = $_POST['backlog'];
    $degree_required = $_POST['degree'];
    $skills_required = $_POST['skills'];
    $certifications = $_POST['certifications'];
    $application_deadline = $_POST['deadline'];
    $soft_skills = $_POST['softskills'];
    $ats_score = $_POST['ats_score'];

    // SQL Query to insert data
    $sql = "INSERT INTO recruiter (companyname, jobrole, mincgpa, backloglimit, degree_required, skills_required, certifications, application_deadline, soft_skills, ats_score) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdisssssi", $companyname, $jobrole, $mincgpa, $backloglimit, $degree_required, $skills_required, $certifications, $application_deadline, $soft_skills, $ats_score);

    if ($stmt->execute()) {
        echo "Job posted successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
