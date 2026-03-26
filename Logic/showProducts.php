<?php 


try {
    $pdo = new PDO('mysql:host=localhost;dbname=warehouses; charset=utf8', 'root', '');

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    $sql = 'SELECT * FROM products';

    $result = $pdo->query($sql);

    echo '<table border=1;>
            <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Description</th>
                <th>Cost</th>
                <th>Space</th>
            </tr>';

    while($row=$result->fetch()){
        echo '<tr><td>' . $row['id'] . '</td><td>' . $row['name'] . '</td><td> ' . $row['description'] . '</td><td>' . $row['cost'] . '</td><td>' . $row['space'] . '</td></tr>';               
    }

    echo '</table>';

}

catch(PDOException $e) {
    $output = 'Unable to connect' . $e;

    echo $output;
}
?>