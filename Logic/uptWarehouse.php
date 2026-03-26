<?php

    include 'Forms/Warehouses/uptWarehouseId.html';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['id'];

        $pdo = new PDO('mysql:host=localhost;dbname=warehouses; charset=utf8', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("SELECT * FROM warehouses WHERE id = ?");
        $stmt->execute([$id]);
        $warehouse = $stmt->fetch();

    }


?>