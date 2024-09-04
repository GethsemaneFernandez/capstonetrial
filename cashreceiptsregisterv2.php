<?php
session_start();

if (!isset($_SESSION['username'])) {
    // Redirect to login page if not logged in//
    header("Location: index.html");
    exit();
    
}
//include("balancesheets.html");//
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
    <title>FINANCE PROTOTYPE</title>
    <link rel="stylesheet" href="cashreceiptsregisterv2.css">
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
            width:24px;
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
            width: 200px;
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
<div class="top-nav">
        <ul>
            <h1 class="logopos">Dashboard</h1>
            <li class="top">
                <a class="top1" href="lalagyannglaman.html">Home</a>
                <div class="dropdown">
                    <div class="dropdown-column">
                        <h3>Key Performance Indicators</h3>
                        <a href="combined.html">Revenue</a>
                        <a href="#">Profitability</a>
                        <a href="#">Cash Flow</a>
                        <a href="#">Liquidity</a>
                        <a href="#">Efficiency</a>
                    </div>
                    <div class="dropdown-column">
                        <h3>Financial Overview</h3>
                        <a href="#">Income Statement Summary</a>
                        <a href="#">Balance Sheet Summary</a>
                        <a href="#">Cash Flow Statement Summary</a>
                    </div>
                    <div class="dropdown-column">
                        <h3>Charts and Graphs</h3>
                        <a href="#">Revenue Trend</a>
                        <a href="#">Profit Margin Trend</a>
                        <a href="#">Cash Flow Chart</a>
                        <a href="#">Financial Ratios Chart</a>
                    </div>
                    <div class="dropdown-column">
                        <h3>Alerts and Notifications</h3>
                        <a href="#">Overdue Payments</a>
                        <a href="#">Low Inventory Levels</a>
                        <a href="#">Financial Performance Alerts</a>
                    </div>
                    <div class="dropdown-column">
                        <h3>Quick Access Links</h3>
                        <a href="#">Frequently Used Reports</a>
                        <a href="#">Recent Transactions</a>
                        <a href="#">Quick Actions</a>
                    </div>            
                </div>
            </li>
            <li class="top">
                <a class="top1" href="homeversion2.html">Transactions</a>
                <div class="dropdown">
                    <div class="dropdown-column">
                        <h3>Point of Sale</h3>
                        <a href="#">Sales Module</a>
                        <a href="#">Inventory Management</a>
                        <a href="#">Customer Management</a>
                        <a href="#">Reporting and Analytics</a>
                        <a href="#">Integrations</a>
                    </div>
                    <div class="dropdown-column">
                        <h3>Account Recievable</h3>
                        <a href="#">Customer Master File</a>
                        <a href="#">Invoice Generation</a>
                        <a href="#">Aging Reports</a>
                        <a href="#">Collections Management</a>
                        <a href="#">Credit Memo Management</a>
                    </div>
                    <div class="dropdown-column">
                        <h3>Account Payable</h3>
                        <a href="#">Vendor Master File</a>
                        <a href="#">Purchase Order Processing</a>
                        <a href="#">Invoice Matching</a>
                        <a href="#">Disbursement Management</a>
                        <a href="#">Check Register</a>
                    </div>
                    <div class="dropdown-column">
                        <h3>Inventory</h3>
                        <a href="#">Item Master File</a>
                        <a href="#">Stock Transfers</a>
                        <a href="#">Physical Inventory</a>
                        <a href="#">Cost of Goods Sold Calculation</a>
                        <a href="#">Inventory Valuation</a>
                    </div>
                    <div class="dropdown-column">
                        <h3>Payroll</h3>
                        <a href="#">Time and Attendance</a>
                        <a href="#">Employee Information</a>
                        <a href="#">Payroll Processing</a>
                    </div>            
                </div>
            </li>
            <li class ="top">
                <a class ="top1" href="cashreceiptsregisterv2.php">Ledgers</a>
                <div class="dropdown">
                    <div class="dropdown-column">
                        <h3>Chart of Accounts</h3>
                        <a href="#">Account Creation</a>
                        <a href="#">Account Modification</a>
                        <a href="#">Account Deletion</a>
                        <a href="#">Account Hierarchy</a>
                    </div>
                    <div class="dropdown-column">
                        <h3>Journal Entries</h3>
                        <a href="#">Journal Entry Creation</a>
                        <a href="#">Journal Entry Modification</a>
                        <a href="#">Journal Entry Deletion</a>
                        <a href="#">Journal Entry Approval</a>
                    </div>
                    <div class="dropdown-column">
                        <h3>Trial Balance</h3>
                        <a href="#">Trial Balance Generation</a>
                        <a href="#">Trial Balance Analysis</a>
                    </div>
                    <div class="dropdown-column">
                        <h3>Adjusting Entries</h3>
                        <a href="#">Adjusting Entry Creation</a>
                        <a href="#">Adjusting Entry Modification</a>
                        <a href="#">Adjusting Entry Deletion</a>
                    </div>
                    <div class="dropdown-column">
                        <h3>Closing Entries</h3>
                        <a href="#">Closing Entry Creation</a>
                        <a href="#">Closing Entry Modification</a>
                        <a href="#">Closing Entry Deletion</a>
                    </div>      
                    <div class="dropdown-column">
                        <h3>Reports and Analytics</h3>
                        <a href="#">Ledger Reports</a>
                        <a href="#">Financial Statement Reports</a>
                        <a href="#">Analytical Reports</a>
                    </div>               
                </div>
            </li>
               
            
            <li class="top">
                <a class="top1" href="balancesheetswithnewui.html">Balance Sheets</a>
                <div class="dropdown">
                    <div class="dropdown-column">
                        <h3>Balance Sheet Generation</h3>
                        <a href="experiment.html">Balance Sheet Calculation</a>
                        <a href="#">Balance Sheet Customization</a>
                        <a href="#">Balance Sheet Comparison</a>
                    </div>
                    <div class="dropdown-column">
                        <h3>Asset Management</h3>
                        <a href="#">Asset Classification</a>
                        <a href="#">Asset Valuation</a>
                        <a href="#">Asset Depreciation</a>
                        <a href="#">Asset Disposal</a>
                    </div>
                    <div class="dropdown-column">
                        <h3>Liability Management</h3>
                        <a href="#">Liability Classification</a>
                        <a href="#">Liability Valuation</a>
                        <a href="#">Interest Expense Calculation</a>
                        <a href="#">Liability Settlement</a>
                    </div>
                    <div class="dropdown-column">
                        <h3>Equity Management</h3>
                        <a href="#">Equity Calculation</a>
                        <a href="#">Equity Components</a>
                        <a href="#">Dividend Calculation</a>
                        <a href="#">Equity Changes</a>
                    </div>
                    <div class="dropdown-column">
                        <h3>Reports and Analytics</h3>
                        <a href="#">Balance Sheet Analysis</a>
                        <a href="#">Financial Statement Comparison</a>
                        <a href="#">Financial Performance Metrics</a>
                    </div>          
                </div>
            
            
            
            </li>

            <li class = "top">
                <a class="top1" href="#settings">Settings</a>
                <div class="dropdown">
                    <div class="dropdown-column">
                        <h3>General Settings</h3>
                        <a href="#">Company Information</a>
                        <a href="#">Currency Settings</a>
                        <a href="#">Time Zone Settings</a>
                    </div>
                    <div class="dropdown-column">
                        <h3>User Management</h3>
                        <a href="#">User Roles</a>
                        <a href="#">User Accounts</a>
                        <a href="#">Password Management</a>
                        <a href="#">User Permissions</a>
                    </div>
                    <div class="dropdown-column">
                        <h3>Chart of Accounts Settings</h3>
                        <a href="#">Account Structure</a>
                        <a href="#">Account Types</a>
                        <a href="#">Account Templates</a>
                    </div>
                    <div class="dropdown-column">
                        <h3>Inventory Settings</h3>
                        <a href="#">Inventory Valuation Methods</a>
                        <a href="#">Stock Levels:</a>
                        <a href="#">Reorder Points</a>
                    </div>
                    <div class="dropdown-column">
                        <h3>Payroll Settings</h3>
                        <a href="#">Tax Tables</a>
                        <a href="#">Overtime Rules</a>
                        <a href="#">Payroll Deductions</a>
                        <a href="#">Direct Deposit Settings</a>
                    </div>          
                </div>
            
            
            
            </li>
        </ul>
        <button type="button" id="logoutButton" class="logout">Log out</button>
    </div>

    <h1>Cash Receipts Register</h1>

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
     <script src="script.js"></script>

</body>
</html>
