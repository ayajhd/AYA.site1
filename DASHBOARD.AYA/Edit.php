<?php
$servername = "localhost";
$username = "root";
$password = "aya2003";
$database = "admin";

// Create connection
$connection = new mysqli($servername, $username, $password, $database);

$id = $_GET['id'] ?? null;
if (!$id) {
    // Handle case when ID is not provided
    // Redirect or display an error message
    exit("Client ID not provided.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($id)) {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $address = $_POST["address"];

    // Update client information in the database
    $sql = "UPDATE clients SET name=?, email=?, phone=?, address=? WHERE id=?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("ssssi", $name, $email, $phone, $address, $id);

    if ($stmt->execute()) {
        // Redirect to index.php after successful update
        header("Location: index.php");
        exit();
    } else {
        echo "Error updating client: " . $connection->error;
    }
}

// Retrieve client information based on ID
$sql = "SELECT * FROM clients WHERE id=?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$client = $result->fetch_assoc();

// Close connection
$connection->close();
?>
