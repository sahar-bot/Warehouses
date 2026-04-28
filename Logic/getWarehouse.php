<?php

    include 'Forms/Warehouses/uptWarehouseId.html';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            session_start();
            $id = $_POST['id'];

            if (empty($id)) {
                $_SESSION['error'] = "Id is required";
                header("Location: ../menu.php?page=getWarehouse");
                exit;
            }

            $pdo = new PDO('mysql:host=localhost;dbname=warehouses; charset=utf8', 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $pdo->prepare("SELECT * FROM warehouses WHERE id = ?");
            $stmt->execute([$id]);
            $warehouse = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$warehouse) {
                $_SESSION['error'] = "Id must exist";
                header("Location: ../menu.php?page=getWarehouse");
            }

            $_SESSION['data'] = $warehouse;

            header("Location: ../menu.php?page=uptWarehouse");
            exit;
        } catch (PDOException $e) {
            $_SESSION['error'] = "Couldn't load data from database";
            header("Location: ../menu.php?page=getWarehouse");
            exit;
        }
    }


?>