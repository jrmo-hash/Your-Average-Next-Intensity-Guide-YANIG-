<?php
$conn = new mysqli("sql312.infinityfree.com", "if0_41131473", "54E6s2OHkn", "if0_41131473_user_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

include 'db_connect.php'; 
// Logic to SELECT * FROM exercise_recommendation goeshere


$sql = "SELECT id, name, email, username, gender FROM users";
$result = $conn->query($sql);

echo "<h2>List of Registered Users</h2>";
echo "<table border='1' cellpadding='10' style='border-collapse: collapse; width: 100%;'>";
echo "<tr style='background: #d12c45; color: white;'>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Username</th>
        <th>Gender</th>
      </tr>";

if ($result->num_rows > 0) {
    // Ilalabas na natin ang bawat row ng data
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $row["id"]. "</td>
                <td>" . $row["name"]. "</td>
                <td>" . $row["email"]. "</td>
                <td>" . $row["username"]. "</td>
                <td>" . $row["gender"]. "</td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='5'>Walang user pang nahanap.</td></tr>";
}
echo "</table>";

$conn->close();
?>