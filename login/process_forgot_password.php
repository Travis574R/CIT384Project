<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Database connection
    $conn = new mysqli("localhost", "admin", "Poop123", "hitboarddb");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve and sanitize inputs
    $emailAddress = $conn->real_escape_string($_POST["emailAddress"]);
    $securityQuestion = $conn->real_escape_string($_POST["securityQuestion"]);
    $securityAnswer = $conn->real_escape_string($_POST["securityAnswer"]);

    // Query to check if the provided inputs match a user in the database
    $query = "SELECT * FROM userstable WHERE emailAddress = ? AND securityQuestion = ? AND securityAnswer = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $emailAddress, $securityQuestion, $securityAnswer);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a matching user exists
    if ($result->num_rows > 0) {
        // User found, proceed with password reset process
        echo "Security validation successful. Please proceed to reset your password.";
    } else {
        // User not found, display error message
        echo "One or more of the fields are wrong.";
    }

    // Close connection
    $stmt->close();
    $conn->close();
} else {
    // Redirect if the request method is not POST
    header("Location: forgot-password.html");
    exit();
}
