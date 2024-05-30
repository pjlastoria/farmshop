<?php

if(isset($_POST['order'])) {
  
  //$order_id = $_POST['order_id'];
  $customer_name = $_POST['customer_name'];
  $order_total =  $_POST['order_total'];
  $items = urldecode($_POST['item_list']);//uri code to string before saving to DB
  $payment_method = $_POST['payment_method'];
  
  $now = strtotime('-6 hour');//EST timezone
  $timestamp = date("Y-m-d H:i:s", $now);

  require_once 'dbh.inc.php';
  require_once 'functions.inc.php';

  // SERVER SIDE INPUT VALIDATION
  if(emptyOrderInput($customer_name, $order_total, $items, $payment_method) !== false) {
    header("location: ../admin/orders.php?error=emptyOrderInput");
    exit();
  }
  if(invalidName($customer_name) !== false) {
    header("location: ../admin/orders.php?error=invalidName");
    exit();
  }
  if(invalidTotal($order_total) !== false) {
    header("location: ../admin/orders.php?error=invalidTotal");
    exit();
  }

  if(isset($_POST['id']) && $_POST['id'] != '') { // UPDATE

    $id = $_POST['id'];

    updateOrder($conn, $id, $customer_name, $order_total, $timestamp, $payment_method, $items);

  } else { // CREATE
  
    createOrder($conn, $customer_name, $order_total, $timestamp, $payment_method, $items);

  }

  //} else {
  //  header("location: ../admin/orders.php");
  //  exit();
  //}
  
}

if(isset($_POST['delete-order'])) {// DELETE

  $id = $_POST['id'];

  require_once 'dbh.inc.php';
  require_once 'functions.inc.php';

  deleteOrder($conn, $id);

}


?>