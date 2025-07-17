<?php
session_start();
include 'db_connection.php'; // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $userType = $_POST["userType"];

    // Query to check the user
    $sql = "SELECT * FROM users WHERE username = ? AND userType = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $userType);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if ($password === $user["password"]) { // For security, use password_verify() if hashed
            $_SESSION["username"] = $username;
            $_SESSION["userType"] = $userType;

            if ($userType == "student") {
                header("Location: index_test.html");
            } else {
                header("Location: recruiter.html");
            }
            exit();
        }
    }
    echo "<script>alert('Invalid credentials!'); window.location.href='login.html';</script>";
}
?>
