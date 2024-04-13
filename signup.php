<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "ebidding");
if ($conn->connect_error) {
    die("Connection Failed : " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $username = $_POST['username'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $number = $_POST['number'];

    // Check if the email already exists in the database
    $stmt_check = $conn->prepare("SELECT * FROM registration WHERE email = ?");
    $stmt_check->bind_param("s", $email);
    $stmt_check->execute();
    $stmt_result_check = $stmt_check->get_result();

    if ($stmt_result_check->num_rows > 0) {
        echo "<script>alert('Email already exists.')</script>";
    } else {
        // Check if passwords match
        if ($password != $confirm_password) {
            echo "<script>alert('passwords donot match.')</script>";
        } else {
            // Hash the password
            $hashed_password = hash('sha256', $password);

            // Prepare and execute SQL statement to insert data into the database
            $stmt = $conn->prepare("INSERT INTO registration (username, gender, email, password, number) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $username, $gender, $email, $hashed_password, $number);

            if ($stmt->execute()) {
                echo "<script> alert('Registration successful.')</script>";
            } else {
                echo "<script> alert('Registration Failed. Please try again...')</script>";
            }

            // Close statement
            $stmt->close();
        }
    }

    // Close statement for email check
    $stmt_check->close();
}

// Close connection
$conn->close();
?>
