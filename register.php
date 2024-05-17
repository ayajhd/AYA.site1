<?php
// Define a variable to hold the registration message
$registrationMessage = "";
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    // Validate form data (you may add more validation as needed)
    if (empty($name) || empty($email) || empty($password)) {
        echo "All fields are required.";
    } else {
        // Connect to the database
        $servername = "localhost"; // Change this if your database is hosted elsewhere
        $username = "root"; // Change this to your database username
        $password = "aya2003"; // Change this to your database password
        $dbname = "admin"; // Change this to your database name
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        // Prepare and execute SQL query to insert data into the Users table
        $sql = "INSERT INTO Users (Name, Email, Password) VALUES ('$name', '$email', '$password')";
        if ($conn->query($sql) === TRUE) {
            // Set the registration message
            $registrationMessage = "Registration successful!";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
        // Close database connection
        $conn->close();
    }
}
?>
