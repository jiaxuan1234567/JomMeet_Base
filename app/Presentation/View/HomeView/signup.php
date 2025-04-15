<?php
require_once '../../../fileRegister.php';
include '../../../_header.php'
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - JomMeet</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="login">
        <h2>Sign Up for JomMeet</h2>
    </div>
    <div class="login-container">
        <form>
            <label for="phone">Phone Number:</label>
            <input type="text" id="phone" name="phone" placeholder="Enter your phone number" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>

            <button class="loginbtn">Sign Up</button>
        </form>
        <div class="reminder">
            <p>Have an account already? <a href="account.php">Log In</a></p>
        </div>
    </div>
</body>

</html>
<?php
include '../../../_footer.php';
?>