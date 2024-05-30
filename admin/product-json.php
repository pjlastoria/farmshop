<?php 

//include("../includes/dbh.inc.php");

session_start();

if(!empty($_SESSION['sales-report'])) {

    echo json_encode($_SESSION['sales-report']);

}

?>