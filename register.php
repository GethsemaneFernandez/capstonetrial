<?php
// Include the connection file
include 'db_connection.php';

// Check if data is being received
if (isset($_POST['date']) && isset($_POST['payor']) && isset($_POST['particulars']) && isset($_POST['orNo']) && isset($_POST['accountCode']) && isset($_POST['debit']) && isset($_POST['credit']) && isset($_POST['accountName'])) {
    $date = $_POST['date'];
    $payor = $_POST['payor'];
    $particulars = $_POST['particulars'];
    $orNo = $_POST['orNo'];
    $accountCode = $_POST['accountCode'];
    $debit = $_POST['debit'];
    $credit = $_POST['credit'];
    $accountName = $_POST['accountName'];

    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO cash_receipts_register (date, payor, particulars, or_no, account_code, debit, credit, account_name) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    // Check for errors in preparation
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    // Bind parameters
    $stmt->bind_param("ssssdddd", $date, $payor, $particulars, $orNo, $accountCode, $debit, $credit, $accountName);

    // Execute the statement
    if ($stmt->execute()) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo "Error: Missing required parameters";
}
?>
