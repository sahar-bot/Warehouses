<?php
    include 'Forms/Warehouses/editWarehouse.php';


    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            session_start();

            $id = $_POST['id'];
            $name = $_POST['name'];
            $location = $_POST['location'];
            $capacity = $_POST['capacity'];

            if (empty($name) || empty($location) || empty($capacity)) {
                $_SESSION['error'] = "All fields must be filled";
                header("Location: ../menu.php?page=uptWarehouse");
                exit;
            }

            $pdo = new PDO('mysql:host=localhost;dbname=warehouses; charset=utf8', 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $pdo->prepare("UPDATE warehouses SET name = ?, location = ?, capacity = ? WHERE id = ?");
            $stmt->execute([$name, $location, $capacity, $id]);

            $_SESSION['success'] = "Warehouse updated";
            header("Location: ../menu.php?page=showWarehouses");
            exit;

        } catch(PDOException $e) {
            $_SESSION['error'] = "Database Error";
            echo $e;
            header("Location: ../menu.php?page=uptWarehouse");
            exit;
        }
    }



?>