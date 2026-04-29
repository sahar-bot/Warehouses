<?php 


try {
    $pdo = new PDO('mysql:host=localhost;dbname=warehouses; charset=utf8', 'root', '');

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    $sql = 'SELECT * FROM warehouses';

    $result = $pdo->query($sql);

    echo '<table border=1;>
            <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Location</th>
                <th>Capacity</th>
                <th>Reserved</th>
            </tr>';

    while($row=$result->fetch()){
        echo '<tr><td>' . $row['id'] . '</td><td>' . $row['name'] . '</td><td> ' . $row['location'] . '</td><td>' . $row['capacity'] . '</td><td>' . $row['reserved'] . '</td></tr>';               
    }

    echo '</table>';

}

catch(PDOException $e) {
    echo "<div class='error'>Unable to connect to database</div>";
}
?>