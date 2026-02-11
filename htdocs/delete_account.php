<?php
session_start();

// Security: Check if the user is actually logged in
if (!isset($_SESSION['user'])) {
    header("Location: index.html");
    exit();
}

$conn = new mysqli("sql312.infinityfree.com", "if0_41131473", "54E6s2OHkn", "if0_41131473_user_db");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Use the session username to delete the record
$currentUser = $_SESSION['user'];

// Prepare the DELETE statement
$sql = "DELETE FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $currentUser);

if ($stmt->execute()) {
    // Clear all session data immediately
    session_unset();
    session_destroy();

    // Success notification and redirect to login.html
    echo "<script>
            alert('Your account has been permanently deleted.');
            window.location.replace('index.html'); 
          </script>";
} else {
    echo "Error deleting record: " . $conn->error;
}

$stmt->close();
$conn->close();
?>