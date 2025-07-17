<?php
include "db_connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $regno = $_POST["regno"];
    $year = $_POST["year"];
    $branch = $_POST["branch"];
    $backlogs = $_POST["backlogs"];
    $cgpa = $_POST["cgpa"];
    $resume = $_FILES["resume"];

    $uploadDir = "uploads/";
    $resumePath = $uploadDir . basename($resume["name"]);

    if (move_uploaded_file($resume["tmp_name"], $resumePath)) {
        $query = "INSERT INTO students (name, regno, year, branch, backlogs, cgpa, resume_path) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssissds", $name, $regno, $year, $branch, $backlogs, $cgpa, $resumePath);
        if ($stmt->execute()) {
            echo "Resume uploaded successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "Error uploading resume.";
    }
}
?>
