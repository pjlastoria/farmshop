<?php
session_start();


if(empty($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}  

include("includes/functions.inc.php");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Handpick Harvest</title>
    <!--<link rel="stylesheet" href="css/reset.css">-->
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/nav-bar.css">
    <link rel="stylesheet" href="css/nav-cart.css">
    <link rel="stylesheet" href="css/carousel.css">
    <link rel="stylesheet" href="css/product-grid.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/signup-login.css">
    <link rel="stylesheet" href="css/modal.css">
    <link rel="stylesheet" href="css/cart.css">
    <link rel="stylesheet" href="css/checkout.css">
</head>
<body>
