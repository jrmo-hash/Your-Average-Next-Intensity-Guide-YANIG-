<?php
session_start();
// Koneksyon sa database
$conn = new mysqli("sql312.infinityfree.com", "if0_41131473", "54E6s2OHkn", "if0_41131473_user_db");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hanapin ang user sa database
    $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // TAMA: Login Success!
        $_SESSION['user'] = $username;
        
        // ETO ANG IMPORTANTE: Dapat saktong 'dashboard.html'
        header("Location: dashboard.php"); 
        exit();
    } else {
        // MALI: Login Failed
        echo "<script>
                alert('Invalid Username or Password!');
                window.location.href = 'index.html';
              </script>";
    }
    $stmt->close();
}
$conn->close();
?>