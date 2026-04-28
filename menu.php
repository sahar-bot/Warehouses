<?php

session_start();

$error = $_SESSION['error'] ?? '';
$success = $_SESSION['success'] ?? '';
$var = $_SESSION['var'] ?? '';


echo "123213:" ;

unset($_SESSION['error'], $_SESSION['success'], $_SESSION['var']);



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Warehouses System</title>


</head>

<body>

    <h1>Warehouses System</h1>

    <div class="menu">

        <div class="menu-option"><button>Warehouses</button>
            <div class="dropdown">
                <a href="?page=showWarehouses">SHOW</a>
                <a href="?page=addWarehouse">ADD</a>
                <a href="?page=getWarehouse">UPDATE</a>
                <a href="?page=delWarehouse">DELETE</a>
            </div>
        </div>
        <div class="menu-option"><button>Products</button>
            <div class="dropdown">
                <a href="?page=showProducts">SHOW</a>
                <a href="?page=addProduct">ADD</a>
                <a href="?page=getProduct">UPDATE</a>
                <a href="?page=delProduct">DELETE</a>
            </div>
        </div>
        <div class="menu-option"><button>Stock</button></div>
        <div class="menu-option"><button>Transfer</button></div>
        <div class="menu-option"><button>History</button></div>
        <div class="menu-option"><button>Statistic</button></div>
        <div class="dropdown">
            <a>For one</a>
            <a>For all</a>
        </div>
    </div>

    </div>


    <div class="main">

        <?php if ($error): ?>
            <div class="error">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="success">
                <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <?php
        $page = $_GET['page'] ?? null;

        switch ($page) {
            case 'showWarehouses':
                include 'Logic/showWarehouses.php';
                break;

            case 'addWarehouse':
                include 'Logic/addWarehouse.php';
                break;

            case 'getWarehouse':
                include 'Logic/getWarehouse.php';
                break;
            
            case 'uptWarehouse':
                include 'Logic/uptWarehouse.php';
                break;

            case 'delWarehouse':
                include 'Forms/Warehouses/delWarehouse.html';
                break;

            case 'showProducts':
                include 'Logic/showProducts.php';
                break;

            case 'addProduct':
                include 'Logic/addProduct.php';
                break;
            
            case 'getProduct';
                include 'Logic/getProduct.php';
                break;

            case 'uptProduct':
                include 'Logic/uptProduct.php';
                break;

            case 'delProduct':
                include 'Logic/delProduct.php';
                break;
        }


        ?>

    </div>



</body>

</html>