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
        $registrationMessage = "All fields are required.";
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

        // Check if the email already exists in the database
        $checkEmailQuery = "SELECT * FROM Users WHERE Email='$email'";
        $result = $conn->query($checkEmailQuery);
        if ($result->num_rows > 0) {
            $registrationMessage = "Email is already registered.";
        } else {
            // Prepare and execute SQL query to insert data into the Users table
            $sql = "INSERT INTO Users (Name, Email, Password) VALUES ('$name', '$email', '$password')";

            if ($conn->query($sql) === TRUE) {
                // Set the registration message
                $registrationMessage = "Registration successful!";
            } else {
                $registrationMessage = "Error: " . $sql . "<br>" . $conn->error;
            }
        }

        // Close database connection
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<!-- Coding by CodingLab | www.codinglabweb.com-->
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Registration or Sign Up form in HTML CSS | CodingLab </title>
    <link rel="stylesheet" href="registed.css">
    <style>
      .message {
        color: green; /* Change the color as needed */
        font-weight: bold;
        background: yellow;
      }
    </style>
   </head>
<body>
 
  <div class="wrapper">
    <h2>Registration</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
      <div class="input-box">
        <input type="text" name="name" placeholder="Enter your name" required>
      </div>
      <div class="input-box">
        <input type="text" name="email" placeholder="Enter your email" required>
      </div>
      <div class="input-box">
        <input type="password" name="password" placeholder="Create password" required>
      </div>
      <div class="input-box">
        <input type="password" name="confirm_password" placeholder="Confirm password" required>
      </div>
      <div class="policy">
        <input type="checkbox" required>
        <h3>I accept all terms & condition</h3>
      </div>
      <div class="input-box button">
        <input type="submit" value="Register Now">
      </div>
      <div class="text">
        <h3>Already have an account? <a href="login.php">Login now</a></h3>
      </div>
    </form>
    <div class="message"><?php echo $registrationMessage; ?></div>
  </div>

</body>
</html>
