<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "businessdb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname,);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        if (password_verify($password, $row['password'])) {
            session_start();
            $_SESSION['username'] = $username;

            header("Location: lalagyannglaman.html");
            //echo "success"; // Send success response//
        } else {
            echo "Invalid password"; // Send error message
        }
    } else {
        echo "User not found"; // Send error message
    }
}

$conn->close();
?>
