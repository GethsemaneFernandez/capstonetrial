<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "accountingdb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
        $stmt->bind_param("ssssssss", $date, $payor, $particulars, $orNo, $accountCode, $debit, $credit, $accountName);

        if ($stmt->execute()) {
            // Redirect to the same page to avoid resubmission
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error: Missing required parameters";
    }
}



// Retrieve data from the database
$query = "SELECT * FROM cash_receipts_register";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cash Receipts Register</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f5f5f5;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: white;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        .input-container {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #fff;
            border: 1px solid #ddd;
        }

        .input-container input {
            margin-right: 10px;
            padding: 8px;
            width: 180px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .input-container button {
            padding: 8px 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .input-container button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

    <h1>Cash Receipts Register</h1>

    <!-- Input Fields for New Row -->
    <div class="input-container">
        <form id="cashReceiptsForm" method="post">
            <input type="date" name="date" id="date" placeholder="Date" required>
            <input type="text" name="payor" id="payor" placeholder="Payor" required>
            <input type="text" name="particulars" id="particulars" placeholder="Particulars" required>
            <input type="text" name="orNo" id="orNo" placeholder="OR No" required>
            <input type="text" name="accountCode" id="accountCode" placeholder="Account Code" required>
            <input type="number" name="debit" id="debit" placeholder="Debit" required>
            <input type="number" name="credit" id="credit" placeholder="Credit" required>
            <input type="text" name="accountName" id="accountName" placeholder="Account Name" required>
            <button type="submit">Add Entry</button>
        </form>
    </div>

    <!-- Table -->
    <table id="cashReceiptsTable">
        <thead>
            <tr>
                <th>Date</th>
                <th>Payor</th>
                <th>Particulars</th>
                <th>OR No</th>
                <th>Account Code</th>
                <th>Debit</th>
                <th>Credit</th>
                <th>Account Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['date']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['payor']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['particulars']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['or_no']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['account_code']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['debit']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['credit']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['account_name']) . "</td>";
                    echo "<td><button onclick=\"editRow(this)\">Edit</button> <button onclick=\"deleteRow(this, " . $row['id'] . ")\">Delete</button></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='9'>No records found.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <script>
        function deleteRow(button, id) {
            if (confirm("Are you sure you want to delete this entry?")) {
                // Remove the row from the frontend
                const row = button.parentNode.parentNode;
                row.parentNode.removeChild(row);

                // Send delete request to server
                const xhr = new XMLHttpRequest();
                xhr.open("POST", "delete_entry.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.send("id=" + encodeURIComponent(id));
            }
        }

        function editRow(button) {
            const row = button.parentNode.parentNode;
            document.getElementById('date').value = row.cells[0].innerText;
            document.getElementById('payor').value = row.cells[1].innerText;
            document.getElementById('particulars').value = row.cells[2].innerText;
            document.getElementById('orNo').value = row.cells[3].innerText;
            document.getElementById('accountCode').value = row.cells[4].innerText;
            document.getElementById('debit').value = row.cells[5].innerText;
            document.getElementById('credit').value = row.cells[6].innerText;
            document.getElementById('accountName').value = row.cells[7].innerText;

            // Remove the row after editing
            row.parentNode.removeChild(row);
        }
    </script>

</body>
</html>
