<?php
$servername = "localhost"; // Change if needed
$username = "root"; // Your MySQL username
$password = ""; // Your MySQL password (leave empty if using XAMPP)
$dbname = "campus_recruitment"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Database Connection Failed"]));
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $company = $_POST['company'];
    $jobrole = $_POST['jobrole'];
    $cgpa = $_POST['cgpa'];
    $backlog = $_POST['backlog'];
    $degree = $_POST['degree'];
    $skills = $_POST['skills'];
    $certifications = $_POST['certifications'] ?? "";
    $deadline = $_POST['deadline'];
    $softskills = $_POST['softskills'] ?? "";
    $ats_score = $_POST['ats_score'];

    // Prepare SQL statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO recruiter (companyname, jobrole, mincgpa, backloglimit, degree_required, skills_required, certifications, application_deadline, soft_skills, ats_score) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdiissssi", $company, $jobrole, $cgpa, $backlog, $degree, $skills, $certifications, $deadline, $softskills, $ats_score);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Job Posted Successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to Post Job"]);
    }

    $stmt->close();
}

$conn->close();
?>
