<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $conn = new mysqli("localhost", "admin", "Poop123", "hitboarddb");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Sanitize and retrieve other inputs
    $firstName = $conn->real_escape_string($_POST["firstName"]);
    $lastName = $conn->real_escape_string($_POST["lastName"]);
    $emailAddress = $conn->real_escape_string($_POST["emailAddress"]);
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT); // Encrypt password
    $securityQuestion = $conn->real_escape_string($_POST["securityQuestion"]);
    $securityAnswer = $conn->real_escape_string($_POST["securityAnswer"]);

    // Define the order of the categories
    $categories = ["Cat 5", "Cat 4", "Cat 3", "Cat 2", "Cat 1", "35+", "45+", "55+", "Too damn old"];

    // Initialize the binary string as all 0s
    $binaryFieldCategory = str_repeat("0", count($categories));

    // Check which categories were selected
    if (isset($_POST["fieldCategory"])) {
        foreach ($_POST["fieldCategory"] as $selectedCategory) {
            // Find the position of the selected category in the list
            $index = array_search($selectedCategory, $categories);
            if ($index !== false) {
                // Set the corresponding bit to 1
                $binaryFieldCategory[$index] = "1";
            }
        }
    }

    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO userstable (firstName, lastName, emailAddress, password, securityQuestion, securityAnswer, fieldCategory) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $firstName, $lastName, $emailAddress, $password, $securityQuestion, $securityAnswer, $binaryFieldCategory);

    // Execute and check for success
    if ($stmt->execute()) {
    echo "Signup successful!";
	} else {
    echo "Error: " . $stmt->error;
	}


    // Close the connection
    $stmt->close();
    $conn->close();
}
