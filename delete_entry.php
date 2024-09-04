<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the connection file
include 'db_connection.php';

// Check if the 'id' parameter is set
if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Prepare SQL statement for deletion
    $stmt = $conn->prepare("DELETE FROM cash_receipts_register WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "Record deleted successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Error: Missing ID parameter";
}

$conn->close();
?>
