<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION["student_data"])) {
    header("Location: index.html");
    exit();
}

$data = $_SESSION["student_data"];
$name = $data["name"];
$cgpa = floatval($data["cgpa"]);
$backlogs = intval($data["backlogs"]);
$campus_drive_id = $data["campus_drive_id"];

$stmt = $conn->prepare("SELECT * FROM recruiters WHERE id = ?");
$stmt->bind_param("i", $campus_drive_id);
$stmt->execute();
$result = $stmt->get_result();
$job = $result->fetch_assoc();

$is_eligible = ($cgpa >= floatval($job["cgpa"])) && ($backlogs <= intval($job["backlogs"]));

$required_skills = array_map('trim', explode(",", strtolower($job["skills_required"])));
$parsed_resume_text = strtolower($_SESSION["parsed_resume_text"] ?? "");
$resume_skills = array_map('trim', explode(" ", $parsed_resume_text));
$missing_skills = array_diff($required_skills, $resume_skills);

$_SESSION["eligibility_result"] = [
    "name" => $name,
    "jobrole" => $job["jobrole"],
    "company" => $job["companyname"],
    "is_eligible" => $is_eligible,
    "missing_skills" => $missing_skills
];

header("Location: eligibility_result.php");
exit();
?>
