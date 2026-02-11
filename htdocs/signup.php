<?php
// 1. SECURITY HEADERS: Pinipigilan nito ang browser na i-save ang page sa memory (cache)
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$conn = new mysqli("sql312.infinityfree.com", "if0_41131473", "54E6s2OHkn", "if0_41131473_user_db");


include 'db_connect.php'; 


// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $gender = $_POST['gender'];

    // 2. CHECK KUNG EXISTING NA (Anti-Duplicate)
    $checkQuery = "SELECT * FROM users WHERE email = ? OR username = ?";
    $stmtCheck = $conn->prepare($checkQuery);
    $stmtCheck->bind_param("ss", $email, $username);
    $stmtCheck->execute();
    $result = $stmtCheck->get_result();

    if ($result->num_rows > 0) {
        // Kapag may duplicate, babalik sa signup page pero dahil sa headers, dapat malinis ang form
        echo "<script>
                alert('Error: Email or Username already exists!'); 
                window.location.replace('signup.html'); 
              </script>";
    } else {
        // 3. INSERT KUNG UNIQUE
        $sql = "INSERT INTO users (name, email, username, password, gender) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $name, $email, $username, $password, $gender);
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bind_param("sssss", $name, $email, $username, $hashed_password, $gender);
        if ($stmt->execute()) {
            echo "<script>
                    alert('Registration Successful!');
                    // window.location.replace ay tinatanggal ang signup page sa history
                    window.location.replace('index.html'); 
                  </script>";
        }
        $stmt->close();
    }
    $stmtCheck->close();
}
$conn->close();
?>