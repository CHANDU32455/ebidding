<?php
// Start or resume the session
session_start();

// Check if user is logged in
if (isset($_SESSION['email'])) {
    // Retrieve user email from session
    $user_email = $_SESSION['email'];

    // Database connection
    $servername = "localhost";
    $username = "root"; // Your MySQL username
    $password = ""; // Your MySQL password
    $dbname = "ebidding"; // Your MySQL database name

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Update user's session status to inactive in the database
    $sql = "UPDATE registration SET sessionstatus = 'inactive' WHERE email = '$user_email'";

    if ($conn->query($sql) === TRUE) {
        // Close the database connection
        $conn->close();

        // Redirect the user to login.html
        header("Location: login.html");
        exit(); // Ensure that script execution stops after the redirect
    } else {
        echo "Error updating user session status: " . $conn->error;
    }
} else {
    // No user logged in
    echo "No user logged in currently";
}
?>
