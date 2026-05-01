<?php
    include 'Forms/Warehouses/addWarehouse.html'; // because executable file is menu.php and not this one
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            session_start();
            $name = $_POST['name'] ?? '';
            $location = $_POST['location'] ?? '';
            $capacity = $_POST['capacity'] ?? '';

            if (empty($name) || empty($location) || empty($capacity)) {
                $_SESSION['error'] = "All fields are required!";
                header("Location: ../menu.php?page=addWarehouse");
                exit;
            }

            else if (!is_numeric($capacity) || $capacity <= 0){
                $_SESSION['error'] = "Capacity must be positive";
                header("Location: ../menu.php?page=addWarehouse"); 
                exit;
            }
            
            else {


            $pdo = new PDO('mysql:host=localhost;dbname=warehouses; charset=utf8', 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "INSERT INTO warehouses (name, location, capacity) VALUES(:name, :location, :capacity)";

            $stmt = $pdo->prepare($sql);

            $stmt->bindValue(':name', $name);
            $stmt->bindValue(':location', $location);
            $stmt->bindValue(':capacity', $capacity);

            $stmt->execute();

            $_SESSION['success'] = "Added new Warehouse!";

            header("Location: ../menu.php?page=addWarehouse");
            exit();

            }

        } catch (PDOException $e) {
            $error = "Database Error";
        }
    }

?>