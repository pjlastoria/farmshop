<?php

//include("includes/dbh.inc.php");

session_start();

if(!empty($_SESSION['cart'])) {
    echo json_encode($_SESSION['cart']);
}

?>
