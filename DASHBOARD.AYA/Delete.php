<?php
$servername = "localhost";
$username = "root";
$password = "aya2003";
$database = "admin";

// Create connection
$connection = new mysqli($servername, $username, $password, $database);

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Check if ID parameter is set in the URL
if(isset($_GET['id'])) {
    // Sanitize ID parameter to prevent SQL injection
    $id = intval($_GET['id']);
    
    // Check if confirmation is received
    if(isset($_GET['confirm']) && $_GET['confirm'] === 'yes') {
        // Prepare SQL statement to delete client by ID
        $sql_delete = "DELETE FROM clients WHERE id = ?";
        
        // Create a prepared statement for delete
        $stmt_delete = $connection->prepare($sql_delete);
        
        // Bind the parameter for delete
        $stmt_delete->bind_param("i", $id);
        
        // Execute the delete statement
        if($stmt_delete->execute()) {
            // Close the delete statement
            $stmt_delete->close();
            
            // Update the IDs in the table to make them consecutive without gaps
            $sql_update = "SET @num := 0;
                           UPDATE clients SET id = @num := (@num+1);
                           ALTER TABLE clients AUTO_INCREMENT = 1;";
            
            if($connection->multi_query($sql_update) === TRUE) {
                // Redirect back to the list of clients after successful deletion and update
                header("Location: index.php");
                exit();
            } else {
                echo "Error updating IDs: " . $connection->error;
            }
        } else {
            echo "Error deleting client: " . $connection->error;
        }
    } else {
        // Show confirmation message
        echo "<script>
                var confirmed = confirm('Are you sure you want to delete this client?');
                if (confirmed) {
                    window.location.href = 'Delete.php?id=$id&confirm=yes';
                } else {
                    window.location.href = 'index.php'; // Redirect back to index.php if not confirmed
                }
              </script>";
    }
}

// Close connection
$connection->close();
?>
