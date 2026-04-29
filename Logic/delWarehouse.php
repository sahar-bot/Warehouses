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
            else {

                $pdo = new PDO('mysql:host=localhost;dbname=warehouses; charset=utf8', 'root', '');
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // $sql = 'SELECT count(*) FROM warehouses where id = :id';

                // $stmt = $pdo->prepare($sql);

                // $stmt->bindValue(':id', $id);

                // $stmt->execute();

                // if($stmt->fetchColumn() > 0) {
                //     $sql = 'DELETE FROM warehouses WHERE id = :id';
                //     $stmt = $pdo->prepare($sql);
                //     $stmt->bindValue(':id', $id);
                //     $stmt->execute();

                //     $_SESSION['success'] = "Warehouse has been deleted";
                // }
                // else {
                //     $_SESSION['error'] = "Warehouse not found";
                // }
                // header('Location: ../menu.php?page=delWarehouse');
                // exit;

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