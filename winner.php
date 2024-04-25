<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Winning Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            text-align: center;
            background-color: #fff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
            font-size: 32px;
            margin-bottom: 20px;
        }

        p {
            color: #666;
            font-size: 18px;
            margin-bottom: 15px;
        }

        p.winner {
            font-weight: bold;
            color: #007bff;
            margin-bottom: 5px;
        }

        p.thank-you {
            color: #28a745;
            font-style: italic;
        }

        .footer {
            margin-top: 30px;
            font-size: 14px;
            color: #999;
        }

        .footer a {
            color: #007bff;
            text-decoration: none;
        }

        @media screen and (max-width: 768px) {
            .container {
                padding: 20px;
            }
            h2 {
                font-size: 28px;
            }
        }
    </style>
</head>
<?php
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

// Query to fetch the highest bid user's email and the corresponding item name
$sql = "SELECT highestbiduser, name FROM biditem WHERE highestbiduser IS NOT NULL";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $winner_email = $row['highestbiduser'];
    $item_name = $row['name'];
} else {
    $winner_email = "No winner found";
    $item_name = "No item found";
}

$conn->close();
?>

<body>
    <div class="container">
        <h2>Winner Announcement</h2>
        <p class="winner">The winner is:</p>
        <p class="winner">Email: <?php echo $winner_email; ?></p>
        <p class="winner">Item Name: <?php echo $item_name; ?></p>
        <p class="thank-you">Thank you for using our service.</p>
    </div>

    <div class="footer">
        <p>Want to bid on more items? <a href="home.html">Visit our bidding platform</a></p>
    </div>
</body>
</html>
