<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>Login</h1>

<?php if (!empty($_SESSION['error'])): ?>
    <div class="error">
        <?php 
            echo $_SESSION['error']; 
            unset($_SESSION['error']);
        ?>
    </div>
<?php endif; ?>

<form method="POST" action="userCheck.php">

    <label>Username</label>
    <input type="text" name="name" required>

    <label>Password</label>
    <input type="password" name="password" required>

    <input type="submit" value="Login">

</form>

</body>
</html>