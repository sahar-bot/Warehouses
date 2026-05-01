<?php
    if ($_SERVER['REQUEST_METHOD'] == "POST") {

        try {
        session_start();

        $warehouse_id = $_POST['warehouse_id'];
        $product_id = $_POST['product_id'];
        $qty = $_POST['qty'];

        if (!is_numeric($qty) || $qty <= 0) {
            $_SESSION['error'] = "Invalid quantity";
            header("Location: ../menu.php?page=simulate");
            exit;
        }

        $pdo = new PDO("mysql:host=localhost;dbname=warehouses;charset=utf8", "root", "");
        
        $stmt = $pdo->prepare("SELECT * FROM warehouses WHERE id = ?");
        $stmt->execute([$warehouse_id]);
        $warehouse = $stmt->fetch();

        if (!$warehouse) {
            $_SESSION['error'] = "Warehouse doesn't exist";
            header("Location: ../menu.php?page=simulate");
            exit;
        }

        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch();

        if (!$product) {
            $_SESSION['error'] = "Product doesn't exist";
            header("Location: ../menu.php?page=simulate");
            exit;
        }

        if ($warehouse['capacity'] < $warehouse['reserved'] + $qty * $product['space']) {
            $_SESSION['error'] = "Impossible action, there is not enough space in the warehouse: " . $warehouse['id'] . " to contain all that";
            header("Location: ../menu.php?page=simulate");
            exit;

        }

        $stmt = $pdo->prepare("INSERT INTO warehouse_stock (warehouse_id, product_id, quantity) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE quantity = quantity + ?");
        $stmt->execute([$warehouse_id, $product_id, $qty, $qty]);

        $stmt = $pdo->prepare("UPDATE warehouses SET reserved = reserved + ? WHERE id = ?");
        $stmt->execute([$qty * $product['space'], $warehouse_id]);

        $_SESSION['success'] = "Operation successfull";
        header("Location: ../menu.php?page=showStock");
        exit;

        } catch(PDOException $e) {
            $_SESSION['error'] = "Database error";
            header("Location: ../menu.php?page=simulate");
            exit;
        }
    }



?>


<form action="Logic/simulate.php" method="POST">
    <label>Warehouse: </label>
    <input type="text" name="warehouse_id">
    <label>Product: </label>
    <input type="text" name="product_id">
    <label>Quantity: </label>
    <input type="text" name="qty">
    <input type="submit" value="Generate">
</form>