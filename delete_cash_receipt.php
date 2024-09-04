<?php
// Include the connection file
include 'db_connection.php';

// Get the ID of the row to be deleted
$id = $_POST['id'];

// Delete the row from the database
$query = "DELETE FROM cash_receipts_register WHERE id = $id";
mysqli_query($conn, $query);

// Redirect back to the main page
header("Location: index.html");
exit();
?>
