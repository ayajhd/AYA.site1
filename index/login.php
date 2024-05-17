<?php
// Initialize the loginMessage variable
$loginMessage = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Connect to the database
    $servername = "localhost"; // Change this if your database is hosted elsewhere
    $username = "root"; // Change this to your database username
    $dbpassword = "aya2003"; // Change this to your database password
    $dbname = "admin"; // Change this to your database name

    $conn = new mysqli($servername, $username, $dbpassword, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare SQL statement to check in clients table
    $stmt = $conn->prepare("SELECT * FROM clients WHERE Email = ? AND Password = ?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $resultClients = $stmt->get_result();

    // Prepare SQL statement to check in users table
    $stmt = $conn->prepare("SELECT * FROM Users WHERE Email = ? AND Password = ?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $resultUsers = $stmt->get_result();

    // Check if user is found in clients table
    if ($resultClients->num_rows > 0) {
        // User is found in clients table (dashboard)
        header("Location: /DASHBOARD.AYA/index.php");
        exit();
        
        
    } elseif ($resultUsers->num_rows > 0) {
        // User is found in Users table (index.html)
        header("Location: index.html");
        exit();
    } else {
        // User is not found in either table
        $loginMessage = "Email or password is incorrect.";
    }

    // Close database connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="login.css">
    <style>
        .message {
            color: green; /* Change the color as needed */
            font-weight: bold;
            background: yellow;
            padding: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <h2>Login</h2>
    <form action="" method="post">
        <div class="input-box">
            <input type="text" name="email" placeholder="Enter your email" required>
        </div>
        <div class="input-box">
            <input type="password" name="password" placeholder="Enter your password" required>
        </div>
        <div class="policy">
            <input type="checkbox" name="terms" required>
            <h3>I accept all terms & condition</h3>
        </div>
        <div class="input-box button">
            <input type="submit" value="Login">
        </div>
        <div class="text">
       
            <h3>Don't have an account? <a href="registration.html">Register now</a></h3>
        </div>
    </form>
</div>

    </div>
</body>
</html>
