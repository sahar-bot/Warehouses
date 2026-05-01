<?php 

    include 'Forms/Products/uptProductId.html';
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        try {
            session_start();
            $id = $_POST['id'];

            if(empty($id)) {
                $_SESSION['error'] = "id is required";
                header("Location: ../menu.php?page=getProduct");
                exit;
            }

            if (!is_numeric($id) || $id < 0) {
                $_SESSION['error'] = "Id must be numeric and positive integer";
                header("Location: ../menu.php?page=getProduct"); 
                exit;
            }

            $pdo = new PDO('mysql:host=localhost;dbname=warehouses; charset=utf8', 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $pdo->prepare("SELECT * FROM Products WHERE id = ?");
            $stmt->execute([$id]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);

            if(!$product) {
                $_SESSION['error'] = "Id must exist";
                header("Location: ../menu.php?page=getProduct");
                exit;
            }

            $_SESSION["data"] = $product;
            header("Location: ../menu.php?page=uptProduct");
            exit;


        } catch(PDOException $e) {
            $_SESSION["error"] = "Database error";
            header("Location: ../menu.php?page=getProduct");
            exit;
        }
    }


?>