<?php
require 'db_connection.php';

$sql = "SELECT id, companyname, jobrole FROM recruiter";
$result = $conn->query($sql);

$drives = [];
while ($row = $result->fetch_assoc()) {
    $drives[] = $row;
}

header('Content-Type: application/json');
echo json_encode($drives);
?>
