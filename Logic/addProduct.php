<?php 
    include 'Forms/Products/addProduct.html';
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        try {
            session_start();
            $name = $_POST['name'];
            $description = $_POST['description'];
            $cost = $_POST['cost'];
            $space = $_POST['space'];

            if (empty($name) || empty($description) || empty($cost) || empty($space)) {
                $_SESSION['error'] = "All fields must be filled";
                header("Location: /menu.php?page=addProduct");
                exit;
            }

            if (!is_numeric($cost) || $cost <= 0) {
                $_SESSION['error'] = "Cost must be a positive number";
                header("Location: ../menu.php?page=addProduct");
                exit;
            }

            if ($space <= 0) {
                $_SESSION['error'] = "Space must be a positive integer";
                header("Location: ../menu.php?page=addProduct");
                exit;
            }

            $pdo = new PDO('mysql:host=localhost;dbname=warehouses; charset=utf8', 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $pdo->prepare("INSERT INTO products (name, description, cost, space) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name,$description,$cost,$space]);


            $_SESSION['success'] = "Product has been updated";
            header("Location: ../menu.php?page=showProducts");
            exit;

        } catch(PDOException $e) {
            $_SESSION['error'] = "Database error";
            echo $e;
            header("Location: ../menu.php?page=addProduct");
            exit;
        }
    }


?>