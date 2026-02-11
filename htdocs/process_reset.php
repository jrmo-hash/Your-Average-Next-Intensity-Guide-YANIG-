<?php
// 1. Koneksyon sa Database
$conn = new mysqli("sql312.infinityfree.com", "if0_41131473", "54E6s2OHkn", "if0_41131473_user_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // 2. I-check kung existing ang email sa database
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // TAMA: Existing ang email. Rekta agad sa page para mapalitan ang password!
        // Sa loob ng process-reset.php, palitan itong line na 'to:
echo "<script>
        alert('Email Verified! Redirecting...');
        window.location.href = 'change_password.php?email=" . urlencode($email) . "';
      </script>";
    } else {
        // MALI: Wala ang email sa database
        echo "<script>
                alert('Error: Email address not found in our records.');
                window.history.back();
              </script>";
    }

    $stmt->close();
}
$conn->close();
?>