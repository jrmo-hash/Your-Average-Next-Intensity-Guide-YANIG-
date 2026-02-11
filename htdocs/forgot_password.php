<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | YANIG</title>
    <link rel="stylesheet" href="forgot_style.css">
</head>
<body>

    <div class="reset-card">
        <h2>Forgot Password?</h2>
        <p>Enter your registered email address and we'll send you a link to reset your password.</p>
        
        <form action="process_reset.php" method="POST">
            <input type="email" name="email" class="input-box" placeholder="Your registered email" required>
            <button type="submit" class="btn-reset">Send Instructions</button>
        </form>
        
        <a href="index.html" class="back-link">← Return to Login</a>
    </div>

</body>
</html>