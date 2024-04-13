<?php
// Database connection
$servername = "localhost";
$username = "root"; // Your MySQL username
$password = ""; // Your MySQL password
$dbname = "ebidding"; // Your MySQL database name

// Check if the bid_id parameter is set
if (isset($_GET['bid_id'])) {
    $bid_id = $_GET['bid_id'];

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    session_start();
    date_default_timezone_set('Asia/Kolkata');

    // Check if the user is logged in
    if (isset($_SESSION['email'])) {
        $user_email = $_SESSION['email'];
        echo $user_email;
        // Pass user ID to JavaScript
        $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'null';
        echo "<script>var userId = $userId;</script>";

        // Fetch user session status from registration table
        $user_query = "SELECT sessionstatus FROM registration WHERE email = '$user_email'";
        $user_result = $conn->query($user_query);

        if ($user_result->num_rows > 0) {
            $user_row = $user_result->fetch_assoc();
            $sessionstatus = $user_row['sessionstatus'];

            // Fetch data from database based on bid ID
            $sql = "SELECT * FROM biditem WHERE id = $bid_id";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Output data of the bid item
                $row = $result->fetch_assoc();
                echo "<h2>" . $row['name'] . "</h2>";
                // Display the image if an image path or URL is available
                if (!empty($row['image'])) {
                    echo "<img src='" . $row['image'] . "' alt='Item Image'>";
                }
                echo "<p><strong>Description:</strong> " . $row['description'] . "</p>";
                echo "<p><strong>Bid Opening Time:</strong> " . $row['openingtime'] . "</p>";
                echo "<p><strong>Bid Closing Time:</strong> " . $row['closingtime'] . "</p>";
                echo "<p><strong>Booked Members Count:</strong> " . $row['memberscount'] . "</p>";
                echo "<p><strong>Current Bid:</strong> $" . $row['currentbid'] . "</p>";

                // Check if the current time is within bid opening and closing times
                $currentTime = time();
                $openingTime = strtotime($row['openingtime']);
                $closingTime = strtotime($row['closingtime']);

                // Check session status
                if ($sessionstatus == "active") {
                    // Display bid input field and submit button only during bid timings
                    if ($currentTime >= $openingTime && $currentTime <= $closingTime) {
                    echo"<form action='submissionbid.php' method='POST'>
                                <label for='userBid'>Your Bid:</label>
                                <input type='number' id='userBid' name='userBid' placeholder='Enter your bid'>
                                <input type='hidden' name='bid_id' value='$bid_id'> <!-- Pass the bid_id as a hidden field -->
                                <button type='submit'>Bid Now</button>
                            </form>";

                    } else {
                        echo "<button disabled>Bid Now</button>";
                        echo "<p>Out of bidding timings.....</p>";
                    }
                } else {
                    echo "<button disabled>Bid Now</button>";
                    echo "<p>You need to be logged in and have an active session to place a bid.</p>";
                }
            } else {
                echo "<p>No bid item found with the provided ID.</p>";
            }
        } else {
            echo "<p>No user found with the provided email.</p>";
        }
    } else {
        echo "<p>You need to be logged in to place a bid.</p>";
    }

    $conn->close();
} else {
    echo "<p>Bid ID parameter is not set.</p>";
}
?>
