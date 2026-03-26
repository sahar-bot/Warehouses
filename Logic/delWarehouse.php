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

                $sql = 'SELECT count(*) FROM warehouses where id = :id';

                $stmt = $pdo->prepare($sql);

                $stmt->bindValue(':id', $id);

                $stmt->execute();

                if($stmt->fetchColumn() > 0) {
                    $sql = 'DELETE FROM warehouses WHERE id = :id';
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(':id', $id);
                    $stmt->execute();

                    $_SESSION['success'] = "Warehouse has been deleted";
                }
                else {
                    $_SESSION['error'] = "Warehouse not found";
                }
                header('Location: ../menu.php?page=delWarehouse');
                exit;
            }
        } catch(PDOException $e) {
            $_SESSION['error'] = 'Database error';
            header('Location: ../menu.php?page=delWarehouse');
            exit;
        }
    
    }

?>