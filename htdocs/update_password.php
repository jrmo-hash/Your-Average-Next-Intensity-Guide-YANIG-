<?php
$conn = new mysqli("sql312.infinityfree.com", "if0_41131473", "54E6s2OHkn", "if0_41131473_user_db");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];

    // 1. Check if passwords match
    if ($new_pass !== $confirm_pass) {
        echo "<script>alert('Passwords do not match!'); window.history.back();</script>";
        exit();
    }

    // 2. Check security requirements (16 chars + symbol gaya ng gusto mo)
    if (strlen($new_pass) < 16 || !preg_match('/[^a-zA-Z0-9]/', $new_pass)) {
        echo "<script>alert('Password must be 16+ characters and have a special symbol!'); window.history.back();</script>";
        exit();
    }

    // 3. Update the database (Plain text muna since prototype pa lang tayo)
    $sql = "UPDATE users SET password = ? WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $new_pass, $email);

    if ($stmt->execute()) {
        echo "<script>
                alert('Success! Your password has been updated.');
                window.location.href = 'index.html';
              </script>";
    } else {
        echo "<script>alert('Error updating password. Try again.'); window.history.back();</script>";
    }

    $stmt->close();
}
$conn->close();
?>