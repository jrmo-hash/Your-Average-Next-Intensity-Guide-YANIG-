<?php
session_start();
// Security: Dapat may user session bago makapasok
if (!isset($_SESSION['user'])) {
    header("Location: index.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - YANIG</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="profile_style.css">
</head>
<body>

   <div class="profile-card">
    <div class="avatar-main">👤</div>
    
    <h2>User Profile</h2>
    <p class="subtitle">Managing your YANIG identity</p>

    <div class="info-box">
        <span class="label">Active Account</span>
        <span class="value">@<?php echo $_SESSION['user']; ?></span>
    </div>

    <div class="actions">
        <a href="logout.php" class="btn-logout">Logout Account</a>
        <a href="dashboard.php" class="btn-back">← Return to Dashboard</a>
    </div>

    <div class="danger-zone" style="margin-top: 20px; border-top: 1px solid #eee; padding-top: 20px;">
        <p style="font-size: 0.85rem; color: #666; margin-bottom: 10px;">Once you delete your account, there is no going back.</p>
        <a href="delete_account.php" 
           class="btn-delete" 
           style="color: #d93025; text-decoration: none; font-weight: 600; font-size: 0.9rem;"
           onclick="return confirm('Are you absolutely sure? This will permanently remove your data.');">
           Delete Account
        </a>
    </div>
</div>

</body>
</html>