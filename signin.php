<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Database connection
    $conn = new mysqli("localhost", "root", "", "ebidding");
    if ($conn->connect_error) {
        die("Connection Failed : " . $conn->connect_error);
    }

    // Prepare SQL statement to retrieve user data by email
    $stmt = $conn->prepare("SELECT * FROM registration WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt_result = $stmt->get_result();

    // Check if any rows are returned
    if ($stmt_result->num_rows > 0) {
        // Fetch user data
        $data = $stmt_result->fetch_assoc();
        
        // Verify hashed password
        $hashed_password_from_db = $data['password'];
        
        // Hash the entered password with the same algorithm used during registration
        $hashed_password = hash("sha256", $password);
        
        if ($hashed_password === $hashed_password_from_db) {
            // Start session and store user data
            session_start();
            $_SESSION['email'] = $email;
        
            // Update status in user_session table to true
            $update_stmt = $conn->prepare("UPDATE registration SET sessionstatus = 'active' WHERE email = ?");
            $update_stmt->bind_param("s", $email);
            $update_stmt->execute();
        
            // Redirect user to home.html
            header("Location: home.html");
            exit; // Ensure script execution stops after redirection
        } else {
            // Set incorrect password notification
            echo "<script>alert('Incorrect password.')</script>";
        }
    } else {
        // Set email not found notification
        echo "<script>alert('HMMM... EMAIL NOT FOUND')</script>";
    }

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
?>
