<?php
// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ebidding";

// Start session and verify user authentication
session_start();
if (!isset($_SESSION['email'])) {
    echo "User is not logged in.";
    exit;
}

// Retrieve user inputs from POST data
if (isset($_POST['userBid']) && isset($_POST['bid_id'])) {
    // Validate userBid and bid_id
    $userBid = filter_var($_POST['userBid'], FILTER_VALIDATE_FLOAT);
    $bid_id = filter_var($_POST['bid_id'], FILTER_VALIDATE_INT);
    
    if ($userBid === false || $bid_id === false) {
        echo "Invalid data submitted.";
        exit;
    }

    $user_email = $_SESSION['email'];

    // Connect to the database
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the initial SQL query
    $sql = "UPDATE biditem SET currentbid = ?, highestbiduser = ? WHERE id = ? AND currentbid < ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    // Bind parameters and execute the statement
    $stmt->bind_param("dsdi", $userBid, $user_email, $bid_id, $userBid);
    $stmt->execute();

    // Check for success and increment memberscount if successful
    if ($stmt->affected_rows > 0) {
        // Alert user of successful bid
        echo "<script>alert('Bid submitted successfully.')</script>";

        // Increment memberscount
        $incrementQuery = "UPDATE biditem SET memberscount = memberscount + 1 WHERE id = ?";
        $incrementStmt = $conn->prepare($incrementQuery);
        if (!$incrementStmt) {
            die("Prepare failed: " . $conn->error);
        }

        // Bind the bid_id parameter and execute the query
        $incrementStmt->bind_param("i", $bid_id);
        $incrementStmt->execute();

        // Close increment statement
        $incrementStmt->close();
    } else {
        echo "Failed to submit bid.";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo "Invalid data submitted.";
}

// It's best practice to avoid using exit() after header redirection; PHP stops the script after sending the header.
?>
