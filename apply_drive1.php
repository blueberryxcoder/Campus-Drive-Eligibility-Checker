<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION["student_data"])) {
    header("Location: index.html");
    exit();
}

$data = $_SESSION["student_data"];
$name = $data["name"];
$regno = $data["regno"];
$cgpa = $data["cgpa"];
$backlogs = $data["backlogs"];
$campus_drive_id = $data["campus_drive_id"];

$stmt = $conn->prepare("INSERT INTO applications (name, regno, cgpa, backlogs, campus_drive_id) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("ssdii", $name, $regno, $cgpa, $backlogs, $campus_drive_id);
$stmt->execute();
$stmt->close();

header("Location: test_eligibility.php");
exit();
?>
