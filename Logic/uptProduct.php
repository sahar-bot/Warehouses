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
                header("Location: ../menu.php?page=uptProduct");
                exit;
            }

            if (!is_numeric($cost) || $cost <= 0) {
                $_SESSION['error'] = "cost must be numeric and positive integer";
                header("Location: ../menu.php?page=uptProduct");
                exit;
            }

            if (!is_numeric($space) || $space <= 0) {
                $_SESSION['error'] = "space must be numeric and positive integer";
                header("Location: ../menu.php?page=uptProduct"); 
                exit;
            }

            $pdo = new PDO('mysql:host=localhost;dbname=warehouses; charset=utf8', 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $pdo->prepare("SELECT space FROM products WHERE id = ?");
            $stmt->execute([$id]);
            $oldProducts = $stmt->fetch();

            $delta = $oldProducts['space'] - $space;
                


            $stmt = $pdo->prepare("SELECT * FROM warehouse_stock WHERE product_id = ?");
            $stmt->execute([$id]);
            $records = $stmt->fetchAll(PDO::FETCH_ASSOC);

            try {
                $pdo->beginTransaction();   // boldy stolen from here: https://www.php.net/manual/en/pdo.transactions.php has been last checked 30.04.2026
                if ($delta < 0) {
                    foreach($records as $record) {
                        $stmt = $pdo->prepare("SELECT capacity, reserved FROM warehouses WHERE id = ?");
                        $stmt->execute([$record['warehouse_id']]);
                        $warehouse = $stmt->fetch();

                        $change = -($delta * $record['quantity']);

                        if ($change + $warehouse['reserved'] > $warehouse['capacity']) {
                            $_SESSION['error'] = "The quantity for the product: " . $name . " is impossible because chaning it that way exceeds the amount of warehouse: " . $record['warehouse_id'];
                            header("Location: ../menu.php?page=showStock");
                            exit;
                        }
                    }
                    // 300 = 300 - (-1) * 100 = 400
                }

                
                foreach($records as $record) {
                    
                    $change = $delta * $record['quantity'];

                    $stmt = $pdo->prepare("UPDATE warehouses SET reserved = reserved - ? WHERE id = ?");
                    $stmt->execute([$change, $record['warehouse_id']]); 
                }
                // 300 = 300 -(+1) * 100
                
    

                $stmt = $pdo->prepare("UPDATE products SET name = ?, description = ?, cost = ?, space = ? WHERE id = ?");
                $stmt->execute([$name, $description, $cost, $space, $id]);

                $pdo->commit(); // boldy stolen from here: https://www.php.net/manual/en/pdo.transactions.php has been last checked 30.04.2026

                $_SESSION['success'] = "Product updated";
                header("Location: ../menu.php?page=showProducts");
                exit;
            } catch(Exception $e) {
                $pdo->rollBack();  // boldy stolen from here: https://www.php.net/manual/en/pdo.transactions.php has been last checked 30.04.2026
                $_SESSION['error'] = "Database error";

            }


        } catch(PDOException $e) {
            $_SESSION['error'] = "Database error";
            header("Location: ../menu.php?page=uptProduct");
            exit;
        }
    }

    
?>




