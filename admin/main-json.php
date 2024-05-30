<?php 

session_start();

if(!empty($_SESSION['graph-data'])) {

    echo json_encode($_SESSION['graph-data']);
    
}

?>