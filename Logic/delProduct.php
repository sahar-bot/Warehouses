<?php
    include "Forms/Products/delProduct.html";
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        try {
            session_start();

            $id = $_POST['id'];

            if (empty($id)) {
                $_SESSION["error"] = "Id must be filled";
                header("Location: ../menu.php?page=delProduct"); 
                exit;
            }

            if (!is_numeric($id) || $id < 0) {
                $_SESSION['error'] = "Id must be numeric and positive integer";
                header("Location: ../menu.php?page=delProduct"); 
                exit;
            }
            
            $pdo = new PDO('mysql:host=localhost;dbname=warehouses; charset=utf8', 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
            $stmt->execute([$id]);
            $record = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$record) {
                $_SESSION['error'] = "Sorry the product doesn't exist";
                header("Location: ../menu.php?page=showProducts");
                exit;
            }

            $stmt = $pdo->prepare("SELECT * FROM warehouse_stock WHERE product_id = ?");
            $stmt->execute([$id]);
            $record = $stmt->fetch();

            if ($record) {
                $_SESSION['error'] = "The product is used on one of the warehouses with id: " . $record['warehouse_id'];
                header("Location: ../menu.php?page=showStock");
                exit;
            }

            $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
            $stmt->execute([$id]);
            $_SESSION['success'] = "Product has been deleted";
            header("Location: ../menu.php?page=showProducts");

            

        } catch(PDOException $e) {
            $_SESSION["error"] = "Database error";
            header("Location: ../menu.php?page=delProduct");
        }
    }


?>