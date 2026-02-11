<?php
$email = isset($_GET['email']) ? $_GET['email'] : '';

if (empty($email)) {
    echo "No email provided!";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change Password | YANIG</title>
    <link rel="stylesheet" href="forgot_style.css">
</head>
<body>
    <div class="reset-card">
        <h2>New Password</h2>
        <p>Updating for: <strong><?php echo htmlspecialchars($email); ?></strong></p>
        
        <form action="update_password.php" method="POST">
            <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
            <input type="password" name="new_password" class="input-box" placeholder="New Password" required>
            <input type="password" name="confirm_password" class="input-box" placeholder="Confirm Password" required>
            <button type="submit" class="btn-reset">Update Password</button>
        </form>
    </div>
</body>
</html>