<?php
    $pdo = new PDO('mysql:host=localhost;dbname=warehouses;charset=utf8','root','');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    $stmt = $pdo->query("SELECT id, name FROM warehouses");
    $warehouses = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($_SERVER['REQUEST_METHOD'] == "POST") {

        session_start();

        $pdo = new PDO('mysql:host=localhost;dbname=warehouses;charset=utf8','root','');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $from = $_POST['from_id'];
        $to = $_POST['to_id'];
        $qty = $_POST['quantity'];
        $product = $_POST['product_id'];

        if (empty($from) || empty($to) || empty($qty) || empty($product)) {
            $_SESSION['error'] = "All fields must be filled";
            header("Location: ../menu.php?page=transfer");
            exit;
        }

        if ($from == $to) {
            $_SESSION['error'] = "Can't choose same objects";
            header("Location: ../menu.php?page=transfer");
            exit;
        }

        if ($qty <= 0) {
            $_SESSION['error'] = "Quantity can't be less or equal to 0";
            header("Location: ../menu.php?page=transfer");
            exit;
        }

        
        // so what do I need to do: 
        // get the space for the product = p.space
        // get the amount of space that will be taken : quantity * p.space 
        // get the amount of available space in the TO warehouse : w.capacity - w.reserved
        // check if it's gonna fit : quantity * p.space < w.capacity - w.reserved
        // if so : UPDATE warehouse_stock SET quantity = quantity - new quantity WHERE id = FROM
        // UPDATE warehouse_stock SET quantity = quantity + new quantity WHERE id = TO

        
        //checks that there is enough items
        $stmt = $pdo->prepare("SELECT quantity FROM warehouse_stock WHERE warehouse_id = ? AND product_id = ?");
        $stmt->execute([$from, $product]);
        $check = $stmt->fetch();

        if (!$check) {
            $_SESSION['error'] = "The product doesn't exist";
            header("Location: ../menu.php?page=transfer");
            exit;
        }
        if ($check['quantity'] < $qty) {
            $_SESSION['error'] = "There is not enough items in the warehouse";
            header("Location: ../menu.php?page=transfer");
            exit;
        }

        // checks how much space will be taken by those items
        $stmt = $pdo->prepare("SELECT space FROM products WHERE id = ?");
        $stmt->execute([$product]);
        $space = $stmt->fetch();

        $change = $space['space'] * $qty;
        

        // checks how much space available in the destination warehouse
        $stmt = $pdo->prepare("SELECT capacity, reserved FROM warehouses WHERE id = ?");
        $stmt->execute([$to]);
        $warehouse = $stmt->fetch();
        
        $available = $warehouse['capacity'] - $warehouse['reserved'];


        if ($change > $available) {
            $_SESSION['error'] = "Sorry but there is not enough space on the warehouse for all of these items";
            header("Location: ../menu.php?page=transfer");
            exit;
        }

        try {

        $pdo->beginTransaction();  // boldy stolen from here: https://www.php.net/manual/en/pdo.transactions.php

        $stmt = $pdo->prepare("UPDATE warehouse_stock SET quantity = quantity - ? WHERE warehouse_id = ? AND product_id = ?");
        $stmt->execute([$qty, $from, $product]);

        $stmt = $pdo->prepare("INSERT INTO warehouse_stock (warehouse_id, product_id, quantity) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE quantity = quantity + ?");
        $stmt->execute([$to, $product, $qty, $qty]);

        $stmt = $pdo->prepare("UPDATE warehouses SET reserved = reserved - ? WHERE id = ?");
        $stmt->execute([$change, $from]);

        $stmt = $pdo->prepare("UPDATE warehouses SET reserved = reserved + ? WHERE id = ?");
        $stmt->execute([$change, $to]);

        $stmt = $pdo->prepare("INSERT INTO transactions (product_id, warehouse_from, warehouse_to, quantity) VALUES (?, ?, ?, ?)");
        $stmt->execute([$product, $from, $to, $qty]);

        $pdo->commit(); // boldy stolen from here: https://www.php.net/manual/en/pdo.transactions.php

        $_SESSION['success'] = "Transfer completed successfully";
        header("Location: ../menu.php?page=showStock");
        exit;

        } catch(Exception $e) {
            $pdo->rollBack(); // boldy stolen from here: https://www.php.net/manual/en/pdo.transactions.php
            $_SESSION['error'] = "Transfer failed";
            header("Location: ../menu.php?page=transfer");
            exit;
        }



        
        

    }

?>

<form method="POST" action="Logic/transfer.php">


    <label>From Warehouse:</label>
    <select name="from_id">
        <option value="">Select Warehouse</option>
        <?php foreach ($warehouses as $w): ?>
            <option value="<?php echo $w['id'] ?>">
                <?php echo $w['name'] . ' ' . $w['id']?>
            </option>
        <?php endforeach; ?>
    </select>


    <label>Product ID:</label>
    <input type="number" name="product_id" min="1">


    <label>To Warehouse:</label>
    <select name="to_id">
        <option value="">Select Warehouse</option>
        <?php foreach ($warehouses as $w): ?>
            <option value="<?php echo $w['id'] ?>">
                <?php echo $w['name'] . ' ' . $w['id']?>
            </option>
        <?php endforeach; ?>
    </select>


    <label>Quantity:</label>
    <input type="number" name="quantity" min="1">

    <br><br>

    <input type="submit" value="Transfer">

</form>