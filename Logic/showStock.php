<?php

try {
    $pdo = new PDO("mysql:host=localhost;dbname=warehouses; charset=utf8", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = "SELECT
                w.name AS warehouse_name,
                p.name AS product_name,
                ws.quantity
            FROM warehouse_stock ws
            JOIN warehouses w ON ws.warehouse_id = w.id
            JOIN products p ON ws.product_id = p.id";
    $result = $pdo->query($stmt);

    echo '<table border=1;>
            <tr>
                <th>Warehouse</th>
                <th>Product</th>
                <th>Quantity</th>
            </tr>';

    while($row=$result->fetch()){
        echo '<tr><td>' . $row['warehouse_name'] . '</td><td>' . $row['product_name'] . '</td><td> ' . $row['quantity'] . '</td></tr>';               
    }

    echo '</table>';
} catch(PDOException $e) {
    echo "<div class='error'>Unable to connect to database</div>";
}


?>



