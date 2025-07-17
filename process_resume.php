<?php
include 'db_connection.php';
require 'vendor/autoload.php';

use Smalot\PdfParser\Parser;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = strtolower(trim($_POST["name"]));
    $regno = trim($_POST["regno"]);
    $cgpa = floatval($_POST["cgpa"]);
    $backlogs = intval($_POST["backlogs"]);
    $campus_drive_id = $_POST["campus_drive"];
    $qualification = strtolower(trim($_POST["qualification"] === "Other" ? $_POST["other_qualification"] : $_POST["qualification"]));
    $resume = $_FILES["resume"]["tmp_name"];

    function extractTextFromPDF($resumePath) {
        $parser = new Parser();
        $pdf = $parser->parseFile($resumePath);
        return strtolower($pdf->getText());
    }

    function extractFirstThreeLines($text) {
        $lines = preg_split('/\r\n|\r|\n/', trim($text));
        return strtolower(implode(" ", array_slice($lines, 0, 3)));
    }

    function extractQualification($text) {
        preg_match('/qualification:\s*([a-zA-Z\s]+)/i', $text, $match);
        return isset($match[1]) ? trim(strtolower($match[1])) : '';
    }

    function namesMatch($enteredName, $resumeText) {
        $enteredWords = array_filter(explode(" ", preg_replace('/\s+/', ' ', $enteredName)));
        $resumeWords = array_filter(explode(" ", preg_replace('/\s+/', ' ', extractFirstThreeLines($resumeText))));
        return empty(array_diff($enteredWords, $resumeWords));
    }

    $parsedText = extractTextFromPDF($resume);
    $resumeQualification = extractQualification($parsedText);

    if (!namesMatch($name, $parsedText)) {
        header("Location: eligibility_result.php?status=error&message=Name mismatch in resume.");
        exit();
    }

    if ($qualification !== $resumeQualification) {
        header("Location: eligibility_result.php?status=error&message=Qualification mismatch.");
        exit();
    }

    session_start();
    $_SESSION["student_data"] = [
        "name" => $name,
        "regno" => $regno,
        "cgpa" => $cgpa,
        "backlogs" => $backlogs,
        "campus_drive_id" => $campus_drive_id
    ];

    header("Location: apply_drive.php");
    exit();
}
?>
