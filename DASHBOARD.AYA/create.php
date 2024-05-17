<?php
$servername = "localhost";
$username = "root";
$password = "aya2003";
$database = "admin";

// Create connection
$connection = new mysqli($servername, $username, $password, $database);
$name = "";
$email = "";
$phone = "";
$address = "";
$password = ""; // Initialize password variable
$errorMessage = "";
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $address = $_POST["address"];
    $password = $_POST["password"]; // Retrieve password from the form

    // Validate email format
    $emailValid = filter_var($email, FILTER_VALIDATE_EMAIL);
    // Validate phone number format
    $phoneValid = preg_match("/^\+?[0-9]+$/", $phone);
    
    if (empty($name) || empty($email) || empty($phone) || empty($address) || empty($password)) {
        $errorMessage = "All fields are required. Please provide the correct information.";
    } elseif (!$emailValid && !$phoneValid) {
        $errorMessage = "Invalid email and phone number format. Please provide valid email and phone number.";
    } elseif (!$emailValid) {
        $errorMessage = "Invalid email format. Please provide a valid email address.";
    } elseif (!$phoneValid) {
        $errorMessage = "Invalid phone number format. Please provide a valid phone number.";
    } else {
        // Check if email or phone already exists
        $checkQuery = "SELECT COUNT(*) as count FROM clients WHERE email = ? OR phone = ?";
        $stmt = $connection->prepare($checkQuery);
        $stmt->bind_param("ss", $email, $phone);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        if ($row['count'] > 0) {
            $errorMessage = "Email or phone number already exists. Please provide different email or phone number.";
        } else {
            // Add new client to database using prepared statement
            $sql = "INSERT INTO clients (name, email, phone, address, password) VALUES (?, ?, ?, ?, ?)";
            $stmt = $connection->prepare($sql);
            $stmt->bind_param("sssss", $name, $email, $phone, $address, $password);
            
            if ($stmt->execute()) {
                $successMessage = "Registered successfully";
                // Reset form fields after successful submission
                $name = "";
                $email = "";
                $phone = "";
                $address = "";
                $password = ""; // Reset password
            } else {
                $errorMessage = "Error adding client: " . $connection->error;
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="container my-5">
        <h2>Nouveaux Client</h2>

        <?php
        if (!empty($errorMessage)) {
            echo "
            <div class='alert alert-warning alert-dismissible fade show' role='alert'>
                <strong>$errorMessage</strong>
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>";
        } elseif (!empty($successMessage)) {
            echo "
            <div class='alert alert-success alert-dismissible fade show' role='alert'>
                <strong>$successMessage</strong>
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>";
        }
        ?>
        <form method="post">
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Name</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="name" value="<?php echo $name; ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Email</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="email" value="<?php echo $email; ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Phone</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="phone" value="<?php echo $phone; ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Address</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="address" value="<?php echo $address; ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Password</label> <!-- Password field -->
                <div class="col-sm-6">
                    <input type="password" class="form-control" name="password" value="<?php echo $password; ?>">
                </div>
            </div>

            <div class="row mb-3">
                <div class="offset-sm-3 col-sm-3 d-grid">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
                <div class="col-sm-3 d-grid">
                    <a class="btn btn-outline-primary" href="index.php" role="button">Cancel</a>
                </div>
            </div>
        </form>
    </div>
    <div class="container my-5">
    <h2>List of Clients</h2>    
    <!-- Button to go to index.php -->
    <div class="mb-3">
        <a class="btn btn-secondary go-to-list-btn" href="index.php" role="button">Go to Clients List</a>
    </div>

    <!-- Form fields -->
    <form method="post">
        <!-- Add your form fields here -->
    </form>
</div>

</body>
</html>
