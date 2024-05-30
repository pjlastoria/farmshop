<?php

if(isset($_POST['product'])) {

    $product_name     = $_POST['product_name'];
    $product_code     = $_POST['product_code'];
    $product_price    = $_POST['product_price'];
    $product_image    = $_POST['product_image'];
    $product_category = ucfirst($_POST['product_category']);
    
    $now = strtotime('-6 hour');//EST timezone
    $timestamp = date("Y-m-d H:i:s", $now);
  
    require_once 'dbh.inc.php';
    require_once 'functions.inc.php';
  
    // SERVER SIDE INPUT VALIDATION
    if(emptyProductInput($product_name, $product_code, $product_price, $product_image, $product_category) !== false) {
      header("location: ../admin/products.php?error=emptyProductInput");
      exit();
    }
    if(invalidProductName($product_name) !== false) {
      header("location: ../admin/products.php?error=invalidName");
      exit();
    }
    if(invalidCode($product_code) !== false) {
      header("location: ../admin/products.php?error=invalidCode");
      exit();
    }
    if(invalidPrice($product_price) !== false) {
      header("location: ../admin/products.php?error=invalidPrice");
      exit();
    }
    if(invalidImgId($product_image) !== false) {
      header("location: ../admin/products.php?error=invalidImageId");
      exit();
    }
    if(imageNotFound($product_image) !== false) {
      header("location: ../admin/products.php?error=imageNotFound");
      exit();
    }
  
    if(isset($_POST['id']) && $_POST['id'] != '') { // UPDATE
  
      updateProduct($conn, $_POST['id'], $product_name, $product_code, $product_price, $product_image, $product_category);
  
    } else { // CREATE
    
      createProduct($conn, $product_name, $product_code, $product_price, $product_image, $product_category, $timestamp);
  
    }
    
  }
  
  if(isset($_POST['delete-product']) && $_POST['delete'] == 1) {// DELETE
  
    require_once 'dbh.inc.php';
    require_once 'functions.inc.php';
  
    deleteProduct($conn, $_POST['id']);
  
  }

  function emptyProductInput($product_name, $product_code, $product_price, $product_image, $product_category) {
    if(empty($product_name) || empty($product_code) || empty($product_price) || empty($product_image) || empty($product_category)) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
  }

  function invalidProductName($name) {
    if(!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
  }

  function invalidPrice($price) {
    if(!preg_match("/^\d{1,2}\.\d{2}$/", $price)) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
  }

  function invalidCode($code) {
    if(!preg_match("/^\d{6}$/", $code)) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
  }

  function invalidImgId($img) {
    if(!preg_match("/^[a-zA-Z0-9_-]*$/", $img)) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
  }

  function imageNotFound($img_id) {
    $header = [ "Authorization: Client-ID SgLcrsYQiyTfooGGecC14_X2psVedtUjT2Riapc9UyQ" ];

    $ch = curl_init("https://api.unsplash.com/photos/" . $img_id);

    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $res = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($res, true);
    
    if($data['errors'][0] == "Couldn't find Photo") {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
  }

// C.R.U.D. FOR PRODUCTS

function createProduct($conn, $name, $code, $price, $img_id, $category, $created_at) {
    
    $price = number_format($price, 2, '.', "");
    
    $sql = "INSERT INTO products (product_name, product_code, product_price, product_image, product_category, time_created) VALUES (?, ?, ?, ?, ?, ?);";

    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../admin/products.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ssdsss", $name, $code, $price, $img_id, $category, $created_at);
    mysqli_stmt_execute($stmt);

    if(mysqli_errno($conn) === 1062) {
      header("location: ../admin/products.php?error=codeTaken");
      exit();
    }
        
    
    mysqli_stmt_close($stmt);

    header("location: ../admin/products.php?result=product_added");
    exit();
}

//READ is in functions.inc.php as get_products()

function updateProduct($conn, $id, $name, $code, $price, $img_id, $category) {
 
    $price = number_format($price, 2, '.', "");
    $sql = "UPDATE products SET product_code=?, product_name=?, product_price=?, product_image=?, product_category=? WHERE product_id=?;";

    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../admin/products.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ssdsss", $code, $name, $price, $img_id, $category, $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    
    header("location: ../admin/products.php?result=product_updated");
    exit();
}

function deleteProduct($conn, $id) {

    $sql = "DELETE FROM products WHERE product_id=?;"; 

    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../admin/products.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("location: ../admin/products.php?result=product_deleted");
    exit();

}

?>