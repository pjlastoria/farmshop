<?php

include("../includes/dbh.inc.php");

function get_order_data() {
    global $conn;
    $sql = "SELECT amount, customer_name, time_created FROM orders WHERE time_created BETWEEN date_sub(now(),INTERVAL 6 DAY) AND now() ORDER BY time_created DESC;";     
                                                      
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: index.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_execute($stmt);

    $query = mysqli_stmt_get_result($stmt);
    $rows = mysqli_fetch_all($query, MYSQLI_ASSOC);
    

    return $rows;

}


function prettify_date($time_created) {

    $timestamp = strtotime('-6 hour');//EST timezone
    $now = date("Y-m-d H:i:s", $timestamp);
    
    $from = date_create($time_created);
    $to = date_create($now);
   
    $interval = date_diff($from, $to);
   
    $format_str = $interval->format('%y year ago,%m month ago,%d day ago,%h hour ago,%i min ago,%s sec ago');
    $vals = explode(',', $format_str);

    foreach($vals as $val) {

        if($val[0] != 0){
            $arr_val = explode(' ', $val);

            if(intval($arr_val[0]) > 1){//pluralize hack

                $arr_val[1] .= 's';
                $val = implode(' ', $arr_val);

            }
            
            return $val;
            break;
        }

    };
    
   
}

function make_revenue_report($order_data) {//

    $revenue_report = get_curr_week();//array("2022-06-24" => 0, "2022-06-25" => 0, ...);

    foreach($order_data as $row){

        $order_date = explode(' ', $row['time_created'])[0];//date string in yyyy-mm-dd format
        
        if(array_key_exists($order_date, $revenue_report)) {
            $revenue_report[$order_date] += $row['amount'];
        } 
        
    }

    return $revenue_report;

}

function make_customer_report($order_data) {//making an array of unique customers for each day

    $customer_report = get_curr_week();//array("2022-06-24" => 0, "2022-06-25" => 0, ...);

    foreach($order_data as $row){

        $date_ordered = explode(' ', $row['time_created'])[0];//date string in yyyy-mm-dd format
        $customer = $row['customer_name'];

        if(!array_key_exists($date_ordered, $customer_report)) {
            continue;
        } 

        if( is_array($customer_report[$date_ordered]) ) { //defaults are [int] 0, not an array

            if( !in_array($customer, $customer_report[$date_ordered]) ) { //if customer is new
                array_push($customer_report[$date_ordered], $customer);      //add to that day and move on
                //continue;
            }

        } else {
            $customer_report[$date_ordered] = array( $customer );
        }
        
    }

    return $customer_report;

}

function get_curr_week() {

    $day_count = 7;
    $days = array(  );

    for($i = 0; $i < $day_count; $i++) {

        $next_date = date('Y-m-d', strtotime('-6 hour, ' . '-' . $i . ' day'));
        $days[$next_date] = 0;

    }

    return array_reverse($days);
}

function make_order_report($order_data) {//

    $order_report = get_curr_week();//array("2022-06-24" => 0, "2022-06-25" => 0, ...);


    foreach($order_data as $row){

        $order_date = explode(' ', $row['time_created'])[0];//date string in yyyy-mm-dd format

        if(array_key_exists($order_date, $order_report)) {
            $order_report[$order_date]++;
        } 
        
    }

    return $order_report;

}

function render_recent_sales($order_data) {

    foreach($order_data as $order){

        $amount = number_format($order['amount'], 2);
        $customer = (strlen($order['customer_name']) < 16) ? $order['customer_name'] : substr($order['customer_name'], 0, 16) . '...';
        $how_long_ago = prettify_date($order['time_created']);
        
        $sale = <<<EOT
            <div class="sale">
                <p class="sale-total">&#36;{$amount}</p>
                <p class="sale-customer">{$customer}</p>
                <p class="sale-time">{$how_long_ago}</p>
            </div>
        EOT;

        echo $sale;
        
    }
}
?>