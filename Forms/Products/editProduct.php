<?php

    $product = $_SESSION['data'] ?? "";
    if (!$product) {
        header("Location: ../menu.php?page=getProduct");
    }


?>


<form method="POST" action="Logic/uptProduct.php" id="uptProduct">
    <label>Id</label>
    <input type="text" name="id" value="<?php echo $product['id']; ?>" readonly>
    <label>New Name</label>
    <input type="text" name="name" value="<?php echo $product['name']; ?>">
    <label>New Description</label>
    <input type="text" name="description" value="<?php echo $product['description']; ?>">
    <label>New Cost</label>
    <input type="text" name="cost" value="<?php echo $product['cost']; ?>">
    <label>New Space</label>
    <input type="text" name="space" value="<?php echo $product['space']; ?>">
    <input type="submit" value="Submit Request">
</form>