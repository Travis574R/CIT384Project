<?php
session_start();
// Database connection
$conn = new mysqli("localhost", "admin", "Poop123", "hitboarddb");
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    //Retrieve and sanitize input
    $email = $conn->real_escape_string($_POST["email"]);
    $password = $_POST["password"];

    //Query to find user by emailAddress
    $query = $conn->prepare("SELECT id, password FROM userstable WHERE emailAddress = ?");
    $query->bind_param("s", $email);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        // User found, verify password
        $user = $result->fetch_assoc();
        if (password_verify($password, $user["password"])) {
            // Password correct, start session_start and redirect
            $_SESSION["userId"] = $user["id"];
            header("Location: ../dashboard/dashboard.php");
            exit();
        } else {
                //Incorrect password
                echo "<script>alert('Wrong password. Please try again'); window.history.back();</script>";
                exit();
        }
    } else {
            // User not found
            echo "<script>alert('No user found with this email. Please try again'); window.history.back();</script>";
            exit();
    }
} else {
        // If accessed without POST. redirect to login page
        header("Location: ../login/login.html");
}

