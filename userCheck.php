<?php
    session_start();
    
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $name = $_POST['name'];
        $password = $_POST['password'];

        $pdo = new PDO("mysql:host=localhost;dbname=warehouses;charset=utf8", "root", "");

        $stmt = $pdo->prepare("SELECT * FROM users WHERE name = ?");
        $stmt->execute([$name]);
        $user = $stmt->fetch();

        if (!$user) {
            $_SESSION['error'] = "There is no such user";
            header("Location: index.php");
            exit;
        }

        if ($user['password'] !== $password) {
            $_SESSION['error'] = "Wrong password";
            header("Location: index.php");
            exit;
        }

        $_SESSION['success'] = "Welcome, " . $user['name'] . "!";
        $_SESSION['access'] = True;
        header("Location: menu.php");
        exit;
    }



?>