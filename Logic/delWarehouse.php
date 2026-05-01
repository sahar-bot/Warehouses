<?php 

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            
            session_start();

            $id = $_POST['id'] ?? '';

            if (empty($id)) {
                $_SESSION['error'] = "Id is empty";
                header('Location: ../menu.php?page=delWarehouse');
                exit;
            }

            if (!is_numeric($id) || $id < 0) {
                $_SESSION['error'] = "Id must be numeric and positive integer";
                header("Location: ../menu.php?page=delWarehouse"); 
                exit;
            }
            else {

                $pdo = new PDO('mysql:host=localhost;dbname=warehouses; charset=utf8', 'root', '');
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $stmt = $pdo->prepare("SELECT * FROM warehouses WHERE id = ?");
                $stmt->execute([$id]);
                $warehouse = $stmt->fetch();
                if (!$warehouse) {
                    $_SESSION['error'] = "Sorry this warehouse doesn't exist";
                    header("Location: ../menu.php?page=showWarehouses");
                    exit;
                }

                
                if ($warehouse['reserved'] > 0) {
                    $_SESSION['error'] = "Can't delete warehouse with items inside";
                    header("Location: ../menu.php?page=showStock");
                    exit;
                }

                $stmt = $pdo->prepare("DELETE FROM warehouses WHERE id = ?");
                $stmt->execute([$id]);


                $_SESSION['success'] = "Warehouse has been deleted";
                header('Location: ../menu.php?page=showWarehouses');
                exit;
                


            }
        } catch(PDOException $e) {
            $_SESSION['error'] = 'Database error';
            header('Location: ../menu.php?page=delWarehouse');
            exit;
        }
    
    }

?>