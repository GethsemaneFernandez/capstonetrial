<?php
// Include the connection file
include 'db_connection.php';

// Get the form data
$date = $_POST['date'];
$payor = $_POST['payor'];
$particulars = $_POST['particulars'];
$orNo = $_POST['orNo'];
$accountCode = $_POST['accountCode'];
$debit = $_POST['debit'];
$credit = $_POST['credit'];
$accountName = $_POST['accountName'];

// Insert the data into the database
$query = "INSERT INTO cash_receipts_register (date, payor, particulars, or_no, account_code, debit, credit, account_name) 
          VALUES ('$date', '$payor', '$particulars', '$orNo', '$accountCode', '$debit', '$credit', '$accountName')";
mysqli_query($conn, $query);

// Redirect back to the main page
header("Location: index.html");
exit();
?>
