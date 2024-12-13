<?php
session_start();
// Start session and check if the user is logged in
if (!isset($_SESSION["userId"])) {
    header("Location: ../login/login.html"); // Redirect to login page if not logged in
    exit();
}

// Database connection
$conn = new mysqli("localhost", "admin", "Poop123", "hitboarddb");
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

include 'get_teammates.php';


// Fetch user details
$userId = $_SESSION["userId"];
$userQuery = $conn->prepare("SELECT firstName, lastName, emailAddress, fieldCategory FROM userstable WHERE id = ?");
$userQuery->bind_param("i", $userId);
$userQuery->execute();
$userResult = $userQuery->get_result();
$userDetails = $userResult->fetch_assoc();
// Define the order of the categories
$categories = ["Cat 5", "Cat 4", "Cat 3", "Cat 2", "Cat 1", "35+", "45+", "55+", "Too damn old"];
$binaryFieldCategory = $userDetails['fieldCategory'];
$selectedCategories = [];
for ($i =0; $i < strlen($binaryFieldCategory); $i++) {
    if ($binaryFieldCategory[$i] === "1") {
        $selectedCategories[] = $categories[$i];
    }
}
$selectedCategoriesString = implode(", ", $selectedCategories);
$userDetails['fieldCategory'] = $selectedCategoriesString;


// Fetch events for the calendar
$eventsQuery = "SELECT * FROM eventstable ORDER BY eventDate ASC";
$eventsResult = $conn->query($eventsQuery);

// Fetch user's events
$myEventsQuery = $conn->prepare("SELECT * FROM usersevents ue JOIN eventstable e
                                ON ue.eventId = e.eventId WHERE ue.userId = ?
                                ORDER BY e.eventDate ASC");
$myEventsQuery->bind_param("i", $userId);
$myEventsQuery->execute();
$myEventsResult = $myEventsQuery->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
    <link rel="icon" type="image/png" href="../images/favicon.png">
    <script src="dashboard.js" defer></script>
</head>
<body>
<header>
    <h1>Dashboard</h1>
    <h2>Welcome <?php echo $userDetails["firstName"]; ?></h2>
    <a href="logout.php" id="logout">Logout</a>
</header>
<main>
    <div class="dashboard-container">
        <!-- Menu -->
        <aside class="menu">
            <button id="myAccountBtn">My Account</button>
            <button id="calendarBtn">Calendar</button>
            <button id="myEventsBtn">My Events</button>
        </aside>

        <!-- Content Area -->
        <section class="content">
            <div id="myAccount" class="tab">
                <h2>My Account</h2>
                <p><strong>First Name:</strong> <?php echo $userDetails["firstName"]; ?></p>
                <p><strong>Last Name:</strong> <?php echo $userDetails["lastName"]; ?></p>
                <p><strong>Email:</strong> <?php echo $userDetails["emailAddress"]; ?></p>
                <p><strong>Field Category:</strong> <?php echo $userDetails["fieldCategory"]; ?></p>
            </div>

            <div id="calendar" class="tab">
                <h2>Calendar</h2>
                <div id="calendarContainer"></div>
                <table class="borderlessCalendar">
                    <tr>
                        <th>Date</th>
                        <th>Event</th>
                        <th>Location</th>
                        <th></th>
                    </tr>
                    <?php while ($event = $eventsResult->fetch_assoc()):
                          $formattedDate = date("m/d/Y", strtotime($event['eventDate']));
                    ?>
                       <tr>
                           <td><?php echo $formattedDate; ?></td>
                           <td><?php echo $event["eventName"]; ?></td>
                           <td><?php echo $event["eventLocation"]; ?></td>
                           <td>
                            <form class="cal-add-event-form">
                                <input type="hidden" name="eventId" value="<?php echo $event["eventId"]; ?>">
                                <button class="cal-add-userEvent" data-event-id="<?php echo $event['eventId']; ?>">Add Event</button>
                            </form>
                           </td>
                       </tr>
                    <?php endwhile; ?>
                </table>
            </div>

            <!-- My events Table-->
            <div id="myEvents" class="tab">

                <form id="addEventForm">
                    <h3>Add New Event</h3>
                    <input type="text" name="eventName" placeholder="Event Name" required>
                    <input type="date" name="eventDate" required>
                    <input type="text" name="eventLocation" placeholder="Event Location" required>
                    <button type="submit">Add Event</button>
                </form>

                <h2>My Events</h2>
                <div id="calendarContainer"></div>
                    <table class="borderlessMyEvents">
                        <tr>
                            <th>Date</th>
                            <th>Event</th>
                            <th>Location</th>
                            <th>Teammates</th>
                            <th></th>
                        </tr>
                             <?php while ($event = $myEventsResult->fetch_assoc()):
                               $formattedDate = date("m/d/Y", strtotime($event['eventDate']));
                             ?>
                        <tr>
                            <td><?php echo $formattedDate; ?></td>
                            <td><?php echo $event["eventName"]; ?></td>
                            <td><?php echo $event["eventLocation"]; ?></td>
                            <td><?php $teammates = getTeammates($conn, $event['eventId'], $userId);
                                      echo $teammates;
                                 ?></td>
                            <td>
                                <button class="remove-event"
                                        data-event-id="<?php echo $event["eventId"]; ?>">Remove</button>
                             </td>
                            <?php endwhile; ?>
                        </tr>
                    </table>
            </div>
        </section>
    </div>
</main>
</body>
</html>