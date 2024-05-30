<?php

function get_sorted_products() {
    global $conn;
    $sql = "SELECT time_created, cart FROM orders WHERE time_created BETWEEN date_sub(now(),INTERVAL 6 DAY) AND now() ORDER BY time_created DESC;";     
                                                        
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: products.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_execute($stmt);

    $query = mysqli_stmt_get_result($stmt);
    $rows = mysqli_fetch_all($query, MYSQLI_ASSOC);
    $sales_week = make_sales_reports($rows);

    $product_list = array();

    foreach($rows as $row){
        //var_dump($row);
        $cart_array = json_decode($row['cart'], true);//array( [0] => array( ["code"]=> string(6) "854776" ["name"]=> string(6) "Celery" ["qty"]=> int(4) ["price"]=> float(1.99) ) );
        
        foreach($cart_array as $ind => $product_data){
            $revenue = $product_data['qty'] * $product_data['price'];

            (array_key_exists($product_data['name'], $product_list)) ?
             $product_list[$product_data['name']]        += $revenue :
             $product_list[$product_data['name']]         = $revenue ;
        }
    }
    
    arsort($product_list);
    return array($product_list, $sales_week);
}
//get_sorted_products();

function get_curr_week() {

    $day_count = 7;
    $days = array(  );

    for($i = 0; $i < $day_count; $i++) {

        $next_date = date('Y-m-d', strtotime('-6 hour, ' . '-' . $i . ' day'));
        $days[$next_date] = 0;

    }

    return array_reverse($days);
}

function make_sales_reports($data) {
    
    $sales_report = array();

    foreach($data as $row){

        $order_date = explode(' ', $row['time_created'])[0];
        $cart_array = json_decode($row['cart'], true);//array( [0] => array( ["code"]=> string(6) "854776" ["name"]=> string(6) "Celery" ["qty"]=> int(4) ["price"]=> float(1.99) ) );
        $week_array = get_curr_week();//array("2022-06-24", "2022-06-25", ...);
        
        foreach($cart_array as $product_data){
            
            $revenue = $product_data['qty'] * $product_data['price'];
            
            if(!array_key_exists($product_data['name'], $sales_report)) {
                $sales_report[$product_data['name']] = $week_array;
            } 
            
            if(array_key_exists($order_date, $sales_report[$product_data['name']])) {
                $sales_report[$product_data['name']][$order_date] += $revenue;
            } 
            
        }
        
    }

    return $sales_report;

}

?>
