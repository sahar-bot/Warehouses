<?php 

    $warehouse = $_SESSION['data'] ?? "";
    if (!$warehouse) {
        header("Location: ../menu.php?page=getWarehouse");
    }

?>

<form method="POST" action="Logic/uptWarehouse.php" id="uptWarehouse">
    <label>Id</label>
    <input type="text" name="id" value="<?php echo $warehouse['id']; ?>" readonly>
    <label>New Name</label>
    <input type="text" name="name" value="<?php echo $warehouse['name'];?>">
    <label>New Location</label>
    <input type="text" name="location" value="<?php echo $warehouse['location']; ?>">
    <label>New Capacity</label>
    <input type="text" name="capacity" value="<?php echo $warehouse['capacity']; ?>">
    <input type="submit" value="Submit Request">
</form>