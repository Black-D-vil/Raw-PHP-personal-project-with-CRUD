<?php
session_start();

// Database configuration
include 'db_connection.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // Sanitize the input data to avoid security risks like SQL Injection
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    // Validate the email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format.";
        header("Location: index2.php");
        exit();
    }

    // Prepare and execute the query
    $stmt = $conn->prepare("SELECT password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // User exists, now verify the password
        $stmt->bind_result($hashed_password);
        $stmt->fetch();
      
        // Verify the password
        if (password_verify($password, $hashed_password)) {
            // Login successful
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $email;

            // Redirect to the homepage or dashboard
            header("Location: index2.php");
            exit();
        } else {
            // Invalid password
            $_SESSION['error'] = "Invalid credentials. Please try again.";
            header("Location: index.php");
            exit();
        }
    } else {
        // Invalid email
        $_SESSION['error'] = "Invalid credentials. Please try again.";
        header("Location: index.php");
        exit();
    }

    $stmt->close();
}

$conn->close();
?>
