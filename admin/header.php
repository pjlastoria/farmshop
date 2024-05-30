<?php 
session_start();

if(!isset($_SESSION["userId"])) {
    header("location: ../login.php?error=notLoggedIn");
    exit();
}

if($_SESSION["userName"] != 'admin') {
    header("location: ../index.php?error=notAdmin");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farm Shop</title>
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="css/admin-nav.css">
    <link rel="stylesheet" href="css/overview.css">
    <link rel="stylesheet" href="css/bar-graph.css">
    <link rel="stylesheet" href="css/orders.css">
    <link rel="stylesheet" href="css/products.css">
    
</head>
<body>

<div id="admin-top-nav" class="nav">
    <div id="admin-nav-wrapper">
        <div id="admin-profile-wrapper">
            <img src="../images/bloomer.gif" alt="bloomer" id="admin-profile-pic">
            <p id="admin-name">Welcome, <?= $_SESSION["userName"]; ?></p>
            <p id="admin-logout"> <a href="../includes/logout.inc.php">Logout</a> </p>
        </div>
    </div>
</div>

<div id="admin-side-menu" class="nav">
    <a href="../"><div id="admin"><img src="../images/admin-icon.svg" alt="Admin"><span>Admin Area</span></div></a>
    <div class="side-menu-tabs">
        <a href="./"><div class="side-menu-tab" id="overview-tab"><img src="../images/overview-icon.svg" alt="Overview"><span>Overview</span></div></a>
        <a href="orders"><div class="side-menu-tab" id="orders-tab"><img src="../images/orders-icon.svg" alt="Orders"><span>Orders</span></div></a>
        <a href="products"><div class="side-menu-tab" id="products-tab"><img src="../images/products-icon.svg" alt="Products"><span>Products</span></div></a>
        <!--<a href="customers.php"><li class="side-menu-tab" id="customers-tab">Customers</li></a>
            <hr id="menu-divider" />
        <li class="side-menu-tab">Settings</li>-->
</div>
</div>