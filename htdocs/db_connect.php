<?php
$servername = "sql312.infinityfree.com";
$username = "if0_41131473"; // Default XAMPP username
$password = "54E6s2OHkn";     // Default XAMPP password is empty
$dbname = "if0_41131473_user_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>