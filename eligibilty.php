<?php
include 'db_connection.php'; // Ensure database connection
require 'vendor/autoload.php'; // Ensure Smalot/PdfParser is loaded

use Smalot\PdfParser\Parser; // ✅ PDF Parser

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $regno = $_POST["regno"];
    $cgpa = floatval($_POST["cgpa"]);
    $backlogs = intval($_POST["backlogs"]);
    $campus_drive_id = $_POST["campus_drive"];
    $resume = $_FILES["resume"]["tmp_name"];

    function extractTextFromPDF($resumePath) {
        $parser = new Parser();
        $pdf = $parser->parseFile($resumePath);
        return strtolower($pdf->getText());
    }

    function extractFirstThreeLines($text) {
        $lines = preg_split('/\r\n|\r|\n/', trim($text));
        return strtolower(implode(" ", array_slice($lines, 0, 3))); // First 3 lines
    }

    function extractSkillsFromPDF($text) {
        $skills_list = ["java", "python", "c++", "html", "css", "javascript", "sql", "machine learning"];
        $resume_skills = [];

        foreach ($skills_list as $skill) {
            if (strpos($text, $skill) !== false) {
                $resume_skills[] = $skill;
            }
        }
        return $resume_skills;
    }

    function checkNameInResume($name, $resumeText) {
        $nameParts = explode(" ", preg_replace('/\s+/', ' ', trim(strtolower($name)))); // Remove extra spaces
        foreach ($nameParts as $part) {
            if (!str_contains($resumeText, $part)) { // Check each part
                return false;
            }
        }
        return true;
    }

    // Extract text from the resume
    $resumeText = extractTextFromPDF($resume);
    $firstThreeLines = extractFirstThreeLines($resumeText);

    // Check if name exists in resume
    if (!checkNameInResume($name, $firstThreeLines)) {
        die("Error: Name does not match the resume.");
    }

    // Extract skills from resume
    $resume_skills = extractSkillsFromPDF($resumeText);

    // Get Campus Drive Details
    $query = "SELECT * FROM recruiter WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $campus_drive_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $job = $result->fetch_assoc();

    $required_cgpa = floatval($job["cgpa"]);
    $max_backlogs = intval($job["backlogs"]);
    $required_skills = explode(",", strtolower($job["skills_required"]));

    // Check Eligibility
    $is_eligible = ($cgpa >= $required_cgpa) && ($backlogs <= $max_backlogs);
    
    // Find Missing Skills
    $missing_skills = array_diff($required_skills, $resume_skills);

    // Redirect to results page with eligibility details
    session_start();
    $_SESSION["eligibility_result"] = [
        "name" => $name,
        "regno" => $regno,
        "cgpa" => $cgpa,
        "backlogs" => $backlogs,
        "is_eligible" => $is_eligible,
        "jobrole" => $job["jobrole"],
        "company" => $job["companyname"],
        "required_skills" => $required_skills,
        "resume_skills" => $resume_skills,
        "missing_skills" => $missing_skills
    ];
    header("Location: eligibility_result.php");
    exit();
}
?>
