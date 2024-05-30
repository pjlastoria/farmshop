<?php

include("dbh.inc.php");

function emptyInputSignup($name, $email, $username, $password, $repeatPassword) {
    if(empty($name) || empty($email) || empty($username) || empty($password) ||  empty($repeatPassword)) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

function invalidUsername($username) {
    if(!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

function invalidEmail($email) {
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

function passwordMatch($password, $repeatPassword) {
    if($password !== $repeatPassword) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

function usernameOrEmailExists($conn, $username, $email) {
    //the ? is a placeholder that avoids sending user data directly to the DB
    $sql = "SELECT * FROM users WHERE userName = ? OR userEmail = ?;";
    //$stmt is a usually the var name used for the final sql statement sent to DB
    
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../signup.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ss", $username, $email);
    mysqli_stmt_execute($stmt);

    $dataResult = mysqli_stmt_get_result($stmt);
    //vars can be assigned inside if statements eventhough it is still type checking the data
    if($row = mysqli_fetch_assoc($dataResult)) {
        return $row;
    } else {
        $result = false;
        return $result;
    }

    mysqli_stmt_close($stmt);
}

function createUser($conn, $name, $email, $username, $password) {
    //the ? is a placeholder that avoids sending user data directly to the DB
    $sql = "INSERT INTO users (userFullName, userEmail, userName, userPassword) VALUES (?, ?, ?, ?);";
    //$stmt is usually the var name used for the final sql statement sent to DB
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../signup.php?error=stmtfailed2");
        exit();
    }
    //must hash the password just in case hackers break into the DB
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $username, $hashedPassword);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("location: ../login?result=accountcreated");
    exit();
}

function emptyInputLogin($username, $password) {
    if(empty($username) || empty($password)) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}


function loginUser($conn, $username, $password) {
    $userNameExists = usernameOrEmailExists($conn, $username, $username);

    if($userNameExists === false) {
        header("location: ../login.php?error=noSuchUser");
        exit();
    }

    $passwordHashed = $userNameExists['userPassword'];
    $checkPassword = password_verify($password, $passwordHashed);
 
    if($checkPassword === false) {
        header("location: ../login.php?error=wrongpassword");
        exit();
    } else if($checkPassword === true) {
        session_start();
        $_SESSION["userId"] = $userNameExists["userId"];
        $_SESSION["userName"] = $userNameExists["userName"];

        ($_SESSION["userName"] == 'admin') ? header("location: ../admin") : header("location: ../") ;
        exit();
    }

}

function showUserNav($username) {

    $admin_link = ($username == 'admin') ? '<span class="top-nav-user-ele admin-link"> <a href="admin">Admin</a> </span>' : '';
    //https://piskel-imgstore-b.appspot.com/img/f3a7c25e-5a00-11ec-bdfe-7bfa3ef7609e.gif
    $userData = <<<EOT
        <div id="user-box" data-user="true" class="top-nav-ele">
            <span class="top-nav-user-ele welcome-text">Welcome, {$username} </span>
            <a href="https://www.flaticon.com/free-icons/user" title="{$username}"><img class="top-nav-user-ele" id="profile-pic" src="./images/user-img.png"></a>
            <span class="top-nav-user-ele logout-link"> <a href="includes/logout.inc.php">Logout</a> </span>
            {$admin_link}
        </div>
    EOT;

    echo $userData;
}

// SHOW PRODUCTS ON THE FRONT END

function get_carousel_products() {
    global $conn;
    
    $sql = "SELECT * FROM products;";

    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../index.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_execute($stmt);

    $query = mysqli_stmt_get_result($stmt);
    $rows = mysqli_fetch_all($query, MYSQLI_ASSOC);
    $counter = 0;

    foreach($rows as $key => $row){
        if($counter == 25) { break; };//carousel should fit exactly 25 products and the rest are displayed in the grid

        $productQuantity = 1;
        $moveUpClass = '';
        $appearClass = '';

        if(isset($_SESSION['cart'])) {
            if(array_key_exists($row['product_code'], $_SESSION['cart'])) {
                $productQuantity = $_SESSION['cart'][$row['product_code']]['quantity'];
                $moveUpClass = 'keep-up';
                $appearClass = 'show';
            }
        }
   
        $product = <<<EOT
            <div class="slide">
                <div class="cover">
                    <img src="images/tomato_basket.png" alt="apples">
                    <div class="inner {$appearClass}" data-code="{$row['product_code']}" data-title="{$row['product_name']}" data-price="{$row['product_price']}" 
                    data-image="{$row['product_image']}" data-quantity="1">
                        <ul>
                            <li class="product-price">&#36;{$row['product_price']}/EA</li>
                            <li class="product-name">{$row['product_name']}</li>
                        </ul>
                        <div class="quantity-wrapper" data-code="{$row['product_code']}" data-title="{$row['product_name']}" data-price="{$row['product_price']}" 
                            data-image="{$row['product_image']}" data-quantity="{$productQuantity}">
                            <button class="griddy-add add">+</button>
                            <div class="quantity-btns-wrapper {$moveUpClass}">
                                <button class="minus">-</button>
                                <input class="quantity" value="{$productQuantity}" readonly>
                                <button class="ghost-btn">+</button>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        EOT;

        echo $product;
        unset($rows[$key]);
        $counter++;
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $rows;
}

function get_grid_products($rows) {

    foreach($rows as $row){

        $productQuantity = 1;
        $moveUpClass = '';

        if(isset($_SESSION['cart'])) {
            if(array_key_exists($row['product_code'], $_SESSION['cart'])) {
                $productQuantity = $_SESSION['cart'][$row['product_code']]['quantity'];
                $moveUpClass = 'keep-up';
            }
        }
        
        $product = <<<EOT
            <div class="grid-ele">
                <div class="grid-ele-cont">
                    <img src="https://source.unsplash.com/{$row['product_image']}/200x200" alt="{$row['product_name']}">
                    <div class="grid-ele-info">
                        <ul>
                            <li class="price">&#36;{$row['product_price']}/lb</li>
                            <li class="title">{$row['product_name']}</li>
                        </ul>
                        <div class="quantity-wrapper" data-code="{$row['product_code']}" data-title="{$row['product_name']}" data-price="{$row['product_price']}" 
                            data-image="{$row['product_image']}" data-quantity="{$productQuantity}">
                            <button class="griddy-add add">+</button>
                            <div class="quantity-btns-wrapper {$moveUpClass}">
                                <button class="minus">-</button>
                                <input class="quantity" value="{$productQuantity}" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        EOT;
        //<button class="grid-add add">+</button>
        //
        echo $product;
    }

}

// CHECKOUT FLOW FUNCTIONALITY

function cart() {

    $total = number_format(0, 2, '.', "");//php equivalent of .toFixed(2)

    if(!empty($_SESSION['cart'])) {
        $index = 1;

        foreach($_SESSION['cart'] as $product){

            $productQty = intval($product['quantity']);
            $productTotal = floatval($product['price']) * $product['quantity'];
            $productTotal = number_format($productTotal, 2, '.', "");

            $total += $productTotal;
            $total  = number_format($total, 2, '.', "");

            $cartElement = <<<EOT
                <div class="cart-grid-ele" data-key="{$product['code']}">
                    <div class="img-cont">
                        <img src="https://source.unsplash.com/{$product['image']}/200x200" alt="">
                        <ul>
                            <li class="cart-grid-ele-info-title">{$product['title']}</li>
                            <li class="cart-grid-ele-info-price"> &#36;{$product['price']}/Qty</li>
                        </ul>
                    </div>
                    <div class="cart-grid-right-wrapper">
                        <div class="cart-grid-ele-info">
                            <p class="product-total" id="product-total-index-{$index}"> &#36;{$productTotal}</p>
                        </div>
                        <div class="cart-grid-ele-btn-wrapper">
                            <a class="remove-product">Remove</a>
                            <div class="cart-grid-ele-btns" id="{$index}" data-code="{$product['code']}" data-title="{$product['title']}" data-price="{$product['price']}" 
                            data-image="{$product['image']}" data-quantity="{$productQty}">
                                <button class="cart-minus-btns">-</button>
                                <input class="product-quantity" type="text" placeholder="{$productQty}" value="{$productQty}">
                                <button class="cart-add-btns">+</button>
                            </div>
                        </div>
                    </div>
                </div>
            EOT;

            echo $cartElement;
            $index++;
        }

    }
    
    return '&#36;' . $total;
}

function checkoutSummary() {

    $total = number_format(0, 2, '.', "");//php equivalent of .toFixed(2)

    if(!empty($_SESSION['cart'])) {
        $index = 1;

        foreach($_SESSION['cart'] as $product){

            $productQty = intval($product['quantity']);
            $productTotal = floatval($product['price']) * $product['quantity'];
            $productTotal = number_format($productTotal, 2, '.', "");

            $total += $productTotal;
            $total  = number_format($total, 2, '.', "");

            $item = <<<EOT
                <div class="checkout-item" data-key="{$product['code']}">
                    <div class="img-wrapper">
                        <img src="https://source.unsplash.com/{$product['image']}/200x200" alt="">
                        <ul>
                            <li>{$product['title']}</li>
                            <li class="item-total"> &#36;{$productTotal}</li>
                        </ul>
                    </div>
                    <div class="item-right-wrapper">
                        <div class="cart-grid-ele-info">
                            <p id="product-total-index-{$index}"> x{$productQty}</p>
                        </div>
                    </div>
                </div>
            EOT;

            echo $item;
            $index++;
        }

    }
    
    return $total;
}


function get_cart_qty() {

    $qty = 0;

    if(!empty($_SESSION['cart'])) {

        foreach($_SESSION['cart'] as $product){
            $qty += intval($product['quantity']);
        }

    }

    echo $qty;
}

// FORM VALIDATION FOR ORDERS

function emptyOrderInput($customer_name, $order_total, $items, $payment_method) {
    if(empty($customer_name) || empty($order_total) || empty($items) || empty($payment_method)) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

function invalidName($customer_name) {
    if(!preg_match("/^[a-zA-Z-' ]*$/", $customer_name)) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

function invalidTotal($order_total) {
    if(!preg_match("/^\d{1,3}\.\d{2}$/", $order_total)) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

// C.R.U.D. FOR ORDERS

function createOrder($conn, $name, $amount, $timestamp, $payment_method, $items) {
    //for($i = 0; $i < 5; $i++) { just in case i want to enter a lot of orders
    $amount = number_format($amount, 2, '.', "");
    $orderId = rand(1000000,9999999);
    $sql = "INSERT INTO orders (order_id, customer_name, amount, time_created, payment_method, cart) VALUES (?, ?, ?, ?, ?, ?);";

    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../admin/orders.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "isdsss", $orderId, $name, $amount, $timestamp, $payment_method, $items);
    mysqli_stmt_execute($stmt);

    while(mysqli_errno($conn) === 1062) {   // if the randomly generated orderId above is already taken, keep trying
        
        $orderId = rand(1000000,9999999);
        mysqli_stmt_bind_param($stmt, "i", $orderId);
        mysqli_stmt_execute($stmt);
  
    }
    //} end of for loop
    mysqli_stmt_close($stmt);

    header("location: ../admin/orders?result=order_added");
    exit();
}

function updateOrder($conn, $id, $name, $amount, $timestamp, $payment_method, $items) {

    $sql = "UPDATE orders SET customer_name=?, amount=?, time_created=?, payment_method=?, cart=? WHERE order_id=?;";

    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../admin/orders.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "sdsssi", $name, $amount, $timestamp, $payment_method, $items, $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("location: ../admin/orders?result=order_updated");
    exit();
}

function get_orders() {
    global $conn;
    
    $sql = "SELECT * FROM orders ORDER BY DATE(time_created);";//DESC

    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: orders.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_execute($stmt);

    $query = mysqli_stmt_get_result($stmt);
    $rows = mysqli_fetch_all($query, MYSQLI_ASSOC);
    $count = count($rows);

    foreach($rows as $row){

        $total = number_format($row['amount'], 2, '.', "");
        $order_date = pretty_date($row['time_created']);
        $item_lists = pretty_item_list(json_decode($row['cart'], true));
        $list = $item_lists[0]; 
        $item_data = json_encode($item_lists[1]);
        
        $product = <<<EOT
            <div class="order">
                <p class="order-id">{$row['order_id']}</p>
                <p class="order-customer">{$row['customer_name']}</p>
                <p class="order-total">&#36;{$total}</p>
                <p class="order-date">{$order_date}</p>
                <p class="order-method">{$row['payment_method']}</p>
                <div class="order-cart">
                    <p class="cart-items">{$list}</p>
                </div>
                <span class="order-action" data-id="{$row['order_id']}" data-customer="{$row['customer_name']}" data-total="{$total}" data-method="{$row['payment_method']}" data-items={$item_data}>
                    <button class="order-action-btn edit-btn" title="edit"><img src="../images/edit.svg" alt="Edit"></button>
                    <button class="order-action-btn delete-btn" title="delete"><img src="../images/delete.svg" alt="Delete"></button>
                </span>
            </div>
        EOT;
        
        echo $product;
    }

    mysqli_close($conn);// this is the last database query on the page so close the connection here

    return $count;//passing number of orders to html for pagination

}

function deleteOrder($conn, $id) {

    $sql = "DELETE FROM orders WHERE order_id=?;"; 

    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../admin/orders.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("location: ../admin/orders?result=order_deleted");
    exit();

}

function pretty_date($input) {

    $date = strtotime($input);
    return date('M j, Y', $date);

}

function pretty_item_list($cart) {//'array([{"code":"241321","name":"Eggplant","qty":9,"price":7.99},{"code":"004958","name":"Tomato","qty":6,"price":2.99},{"code":"125433","name":"Broccoli","qty":5,"price":3.99},{"code":"982340","name":"Garlic","qty":2,"price":0.99},{"code":"981347","name":"Mushrooms","qty":7,"price":6.99}])'
    $cart_data = $cart;
    $itemStr = '';

    foreach($cart as $item_data => $data) { 
        $name = $cart_data[$item_data]['name'];
        $cart_data[$item_data]['name'] = preg_replace('/\s+/', '_', $name);

        $itemStr .= $data['qty'] . ' ' . $data['name'] . ', '; //&& substr($item, -1) != 's'
    }
    
    return array(substr($itemStr, 0, -2), $cart_data);

}

function get_product_options() {

    global $conn;
    
    $sql = "SELECT * FROM products;";

    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: orders.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_execute($stmt);

    $query = mysqli_stmt_get_result($stmt);
    $products = mysqli_fetch_all($query, MYSQLI_ASSOC);

    foreach($products as $product){

        $name = $product['product_name'];
        $name_with_dashes = preg_replace('/\s+/', '_', $name);//replacing spaces with dashes inside value for easier use on front end

        $code =  $product['product_code'];
        $price = $product['product_price'];
        
        echo '<option value="' . $code . ' ' . $name_with_dashes . ' ' . $price . '">' . $name . '</option>';

    }

    //mysqli_close($conn);

}

// READ PRODUCTS

function get_products() {
    global $conn;
    
    $sql = "SELECT * FROM products;";

    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: products.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_execute($stmt);

    $query = mysqli_stmt_get_result($stmt);
    $rows = mysqli_fetch_all($query, MYSQLI_ASSOC);
    $count = count($rows);

    foreach($rows as $row){
        
        $img_path = 'http://source.unsplash.com/' . $row['product_image'] . '/200x200';// '../product-images/' . strtolower($row['product_name']) . '.jpg';
        $price = number_format($row['product_price'], 2, '.', "");//
        $product_date = pretty_date($row['time_created']);
        
        $product = <<<EOT
            <div class="table-product">
                <div class="product-image"><img src={$img_path} /></div>
                <p class="product-name">{$row['product_name']}</p>
                <p class="product-price">&#36;{$price}</p>
                <p class="product-date">{$product_date}</p>
                <p class="product-category">{$row['product_category']}</p>
                <p class="product-id">{$row['product_code']}</p>
                <span class="product-action" data-id="{$row['product_id']}" data-code="{$row['product_code']}" data-name="{$row['product_name']}" data-price="{$row['product_price']}" data-image="{$row['product_image']}" data-category="{$row['product_category']}"">
                    <button class="product-action-btn edit-btn" title="edit"><img src="../images/edit.svg" alt="Edit"></button>
                    <button class="product-action-btn delete-btn" title="delete"><img src="../images/delete.svg" alt="Delete"></button>
                </span>
            </div>
        EOT;
        
        echo $product;
    }

    mysqli_close($conn);// this is the last database query on the page so close the connection here

    return $count;//passing number of products to html for pagination

}

function upload_product_img($img_name) {
    
    if (($img_name != "")){
        // Where the file is going to be stored
            $target_dir = "../images/";
            $file = $_FILES['project_image']['name'];
            $path = pathinfo($file);
    
            $filename = $path['filename'];
            $ext = $path['extension'];
            $temp_name = $_FILES['project_image']['tmp_name'];
            $path_filename_ext = $target_dir . $filename . "." . $ext;//this is what will be saved in DB
    
        // Check if file already exists
        if (file_exists($path_filename_ext)) {

            header("location: ../projects?error=imgexists");
            exit();

        } else {

            move_uploaded_file($temp_name,$path_filename_ext);
            return $path_filename_ext;

        }
    }

}

?>