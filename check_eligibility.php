<?php
include "db_connect.php"; // Database connection
require 'vendor/autoload.php'; // Composer autoload for PDF processing

use Spatie\PdfToText\Pdf;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $regno = $_POST["regno"]; // Student registration number
    $drive_id = $_POST["drive_id"]; // Selected drive from dropdown
    $resume = $_FILES["resume"]["tmp_name"];

    // Ensure resume directory exists
    $uploadDir = "uploads/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Save Resume
    $resumePath = $uploadDir . basename($_FILES["resume"]["name"]);
    move_uploaded_file($_FILES["resume"]["tmp_name"], $resumePath);

    // Extract Text from Resume
    $resumeText = Pdf::getText($resumePath);
    
    // Extract Data Using Python Script
    $pythonCommand = escapeshellcmd("python3 extract_resume_data.py '$resumePath'");
    $resumeData = json_decode(shell_exec($pythonCommand), true);

    if (!$resumeData) {
        die("<h3 style='color:red;'>❌ Resume processing failed!</h3>");
    }

    // Fetch student details from database
    $stmt = $conn->prepare("SELECT * FROM students WHERE regno = ?");
    $stmt->bind_param("s", $regno);
    $stmt->execute();
    $studentResult = $stmt->get_result();

    // Fetch recruiter criteria
    $stmt = $conn->prepare("SELECT * FROM recruiter WHERE id = ?");
    $stmt->bind_param("i", $drive_id);
    $stmt->execute();
    $recruiterResult = $stmt->get_result();

    if ($studentResult->num_rows > 0 && $recruiterResult->num_rows > 0) {
        $student = $studentResult->fetch_assoc();
        $recruiter = $recruiterResult->fetch_assoc();

        // Combine Student Database Data with Resume Extracted Data
        $student_cgpa = max($student["cgpa"], $resumeData["cgpa"]);
        $student_skills = array_unique(array_merge(explode(",", strtolower($student["skills"])), $resumeData["skills"]));
        $student_certs = array_unique(array_merge(explode(",", strtolower($student["certifications"])), $resumeData["certifications"]));

        // Eligibility Check
        $eligible = true;
        $suggestions = [];

        if ($student_cgpa < $recruiter["mincgpa"]) {
            $eligible = false;
            $suggestions[] = "Improve CGPA to at least " . $recruiter["mincgpa"];
        }

        if ($student["backlogs"] > $recruiter["backloglimit"]) {
            $eligible = false;
            $suggestions[] = "Reduce backlogs to max " . $recruiter["backloglimit"];
        }

        $required_skills = explode(",", strtolower($recruiter["skills_required"]));
        $missing_skills = array_diff($required_skills, $student_skills);
        if (!empty($missing_skills)) {
            $eligible = false;
            $suggestions[] = "Gain these required skills: " . implode(", ", $missing_skills);
        }

        $required_certs = explode(",", strtolower($recruiter["certifications"]));
        $missing_certs = array_diff($required_certs, $student_certs);
        if (!empty($missing_certs)) {
            $eligible = false;
            $suggestions[] = "Complete these certifications: " . implode(", ", $missing_certs);
        }

        // Display Eligibility
        echo "<h2>Eligibility Check for " . htmlspecialchars($student["name"]) . "</h2>";
        echo "<p>Drive: " . htmlspecialchars($recruiter["companyname"]) . " - " . htmlspecialchars($recruiter["jobrole"]) . "</p>";

        if ($eligible) {
            echo "<h3 style='color:green;'>✅ You are eligible for this drive!</h3>";
        } else {
            echo "<h3 style='color:red;'>❌ You are NOT eligible.</h3>";
            echo "<h4>Enhancements Needed:</h4><ul>";
            foreach ($suggestions as $suggestion) {
                echo "<li>" . htmlspecialchars($suggestion) . "</li>";
            }
            echo "</ul>";
        }
    } else {
        echo "<h3 style='color:red;'>Student or Drive not found!</h3>";
    }

    $stmt->close();
    $conn->close();
}
?>
