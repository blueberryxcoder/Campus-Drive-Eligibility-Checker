<?php
include 'db_connection.php'; // Ensure database connection
require 'vendor/autoload.php'; // Ensure Smalot/PdfParser is loaded

use Smalot\PdfParser\Parser; // ✅ Move 'use' to the top

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $regno = $_POST["regno"];
    $cgpa = floatval($_POST["cgpa"]);
    $backlogs = intval($_POST["backlogs"]);
    $campus_drive_id = $_POST["campus_drive"];
    $resume = $_FILES["resume"]["tmp_name"];

    function extractSkillsFromPDF($resumePath) {
        $parser = new Parser(); // ✅ Now 'use' works correctly
        $pdf = $parser->parseFile($resumePath);
        $text = strtolower($pdf->getText());
        $skills_list = ["java", "python", "c++", "html", "css", "javascript", "sql", "machine learning"];
        $resume_skills = [];

        foreach ($skills_list as $skill) {
            if (strpos($text, $skill) !== false) {
                $resume_skills[] = $skill;
            }
        }
        return $resume_skills;
    }

    $resume_skills = extractSkillsFromPDF($resume);

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
