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

            if (!is_numeric($capcity) || $capacity <= 0) {
                $_SESSION['error'] = "capacity must be numeric and positive integer";
                header("Location: ../menu.php?page=uptWarehouse"); 
                exit;
            }

            $pdo = new PDO('mysql:host=localhost;dbname=warehouses; charset=utf8', 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $pdo->prepare("SELECT * FROM warehouses WHERE id = ?");
            $stmt->execute([$id]);
            $warehouse = $stmt->fetch();

            if ($capacity < $warehouse['reserved']) {
                $_SESSION['error'] = "Sorry but the amount of space that is currently reserved bigger than the new capacity amount";
                header("Location: ../menu.php?page=showWarehouses");
                exit;
            }

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