<?php
session_start();
if (!isset($_SESSION["eligibility_result"])) {
    echo "No eligibility data found!";
    exit();
}

$result = $_SESSION["eligibility_result"];
$is_eligible = $result["is_eligible"] ? "✅ Eligible" : "❌ Not Eligible";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eligibility Results</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        header {
            background-color: #007bff;
            color: white;
            text-align: center;
            padding: 15px;
            font-size: 24px;
            font-weight: bold;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        .section {
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 8px;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
        }
        .student-info {
            background: #ffebcd;
        }
        .eligibility-status {
            background: #d1ecf1;
            font-size: 20px;
            text-align: center;
            font-weight: bold;
        }
        .job-info {
            background: #e8f5e9;
        }
        .missing-skills {
            background: #f8d7da;
        }
        h2 {
            margin-top: 0;
            font-size: 22px;
        }
        ul {
            padding-left: 20px;
        }
    </style>
</head>
<body>
    <header>📜 Eligibility Results</header>

    <div class="container">
        <!-- Student Details -->
        <div class="section student-info">
            <h2>👨‍🎓 Student Details</h2>
            <p><strong>Name:</strong> <?= $result["name"] ?></p>
            <p><strong>Registration No:</strong> <?= $result["regno"] ?></p>
            <p><strong>CGPA:</strong> <?= $result["cgpa"] ?></p>
            <p><strong>Backlogs:</strong> <?= $result["backlogs"] ?></p>
        </div>

        <!-- Eligibility Status -->
        <div class="section eligibility-status">
            <h2>🔍 Eligibility Status</h2>
            <p><?= $is_eligible ?></p>
        </div>

        <!-- Job Information -->
        <div class="section job-info">
            <h2>🏢 Job Details</h2>
            <p><strong>Company:</strong> <?= $result["company"] ?></p>
            <p><strong>Job Role:</strong> <?= $result["jobrole"] ?></p>
            <p><strong>Required Skills:</strong> <?= implode(", ", $result["required_skills"]) ?></p>
        </div>

        <!-- Missing Skills -->
        <?php if (!empty($result["missing_skills"])): ?>
        <div class="section missing-skills">
            <h2>⚠️ Missing Skills</h2>
            <p>You need to improve the following skills to be fully eligible:</p>
            <ul>
                <?php foreach ($result["missing_skills"] as $skill): ?>
                    <li><?= ucfirst($skill) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php else: ?>
        <div class="section">
            <h2>✅ Skills Match</h2>
            <p>You have all the required skills for this job!</p>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>
