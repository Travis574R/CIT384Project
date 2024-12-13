<?php
session_start();
if (!isset($_SESSION["userId"])) {
    echo "You must be logged in to remove an event.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $conn = new mysqli("localhost", "admin", "Poop123", "hitboarddb");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $userId = $_SESSION["userId"];
    $data = json_decode(file_get_contents("php://input"), true);
    $eventId = $data["eventId"];

    // Remove the event from the user's events
    $removeEventQuery = $conn->prepare("DELETE FROM usersevents WHERE userId = ? AND eventId = ?");
    $removeEventQuery->bind_param("ii", $userId, $eventId);

    if ($removeEventQuery->execute()) {
        echo "Event removed successfully!";
    } else {
        echo "Error removing event: " . $conn->error;
    }

    $conn->close();
}
?>
