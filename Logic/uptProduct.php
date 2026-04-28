<?php
    include "Forms/Products/editProduct.php";

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        try {
            session_start();

            $id = $_POST['id'];
            $name = $_POST['name'];
            $description = $_POST['description'];
            $cost = $_POST['cost'];
            $space = $_POST['space'];

            if(empty($name) || empty($description) || empty($cost) || empty($space)) {
                $_SESSION['error'] = "All fields must be filled";
                header("Location: ../menu.php?page=uptWarehouse");
                exit;
            }

            $pdo = new PDO('mysql:host=localhost;dbname=warehouses; charset=utf8', 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $pdo->prepare("UPDATE Products SET name = ?, description = ?, cost = ?, space = ? WHERE id = ?");
            $stmt->execute([$name, $description, $cost, $space, $id]);

            $_SESSION['success'] = "Product updated";
            header("Location: ../menu.php?page=showProducts");
            exit;


        } catch(PDOException $e) {
            $_SESSION['error'] = "Database error";
            header("Location: ../menu.php?page=uptProduct");
            exit;
        }
    }

?>