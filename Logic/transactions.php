<?php

    try {
        $pdo = new PDO("mysql:host=localhost;dbname=warehouses; charset=utf8", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->query("SELECT * FROM transactions");

        echo "<table border=1;>
                <tr>
                    <th>Product</th>
                    <th>Warehouse From</th>
                    <th>Warehouse To</th>
                    <th>Quantity</th>
                    <th>Date</th>
                </tr>
        ";
        while($row = $stmt->fetch()) {
            echo "<tr><td>" . $row['product_id'] . "</td><td>" . $row['warehouse_from'] . "</td><td>" . $row['warehouse_to'] . "</td><td>" . $row['quantity'] . "</td><td>" . $row['date_moved'] . "</td></tr>";
        }

        echo "</table>";

    } catch(PDOException $e) {
        $_SESSION['error'] = "Database error";
        header("Location: ../menu.php?page=transactions");
        exit;
    }




?>