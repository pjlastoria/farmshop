<?php 
include("header.php");
include("main-data.php"); 

$recent_sales = get_order_data();
$sales_graph_data = array();

$sales_graph_data['Revenue'] =   make_revenue_report($recent_sales);
$sales_graph_data['Customers'] = make_customer_report($recent_sales);
$sales_graph_data['Orders'] =    make_order_report($recent_sales);

$_SESSION['graph-data'] = $sales_graph_data;

$total_revenue = array_sum($sales_graph_data['Revenue']);
$total_customers = get_total_customers($sales_graph_data['Customers']);
$total_orders = array_sum($sales_graph_data['Orders']);

function get_total_customers($customers) {

    $unique_customers = 0;

    foreach($customers as $customer) {
        
        if( $customer != 0 ) {
            $unique_customers += count($customer);
        }
    }
    
    return $unique_customers;
}

?>


<div id="admin-main">
    <h1 id="admin-title">Overview</h1>
    <div id="main-flex">
        <div id="main-left-wrapper">
            <div class="row-3">
                <div class="top-left-main component active-tab" data-heading="Revenue">
                    <div class="graph-tab">
                        <img src="../images/up-arrow.svg" alt="arrow" class="graph-tab-arrow">
                        <ul class="graph-tab-text">
                            <li id="revenue" class="graph-tab-name">Revenue</li>
                            <li id="revenue-amount" class="graph-tab-amount">$<?= ($total_revenue >= 10000) ? number_format($total_revenue, 0) : number_format($total_revenue, 2); ?></li>
                        </ul>
                        <p id="revenue-difference" class="graph-tab-difference">+$<?= number_format($total_revenue, 2); ?> From Last Week</p>
                    </div>
                </div>
                <div class="top-left-main component" data-heading="Customers">
                    <div class="graph-tab">
                        <img src="../images/down-arrow.svg" alt="arrow" class="graph-tab-arrow">
                        <ul class="graph-tab-text">
                            <li id="cost" class="graph-tab-name">Customers</li>
                            <li id="cost-amount" class="graph-tab-amount"><?= $total_customers ?></li>
                        </ul>
                        <p id="cost-difference" class="graph-tab-difference">+<?= $total_customers; ?> From Last Week</p>
                    </div>
                </div>
                <div class="top-left-main component" data-heading="Orders">
                    <div class="graph-tab">
                        <img src="../images/up-arrow.svg" alt="arrow" class="graph-tab-arrow">
                        <ul class="graph-tab-text">
                            <li id="profit" class="graph-tab-name">Orders</li>
                            <li id="profit-amount" class="graph-tab-amount"><?= $total_orders; ?></li>
                        </ul>
                        <p id="profit-difference" class="graph-tab-difference">+<?= $total_orders; ?> From Last Week</p>
                    </div>
                </div>
            </div>
            <h3 id="graph-title">Revenue</h3>
            <div class="graph-cont component">
                
                <?php include("bar-graph.php"); ?>
            </div>

        </div>
        <div id="main-right-wrapper">
        <h3 id="main-right-title">Recent Sales</h3>
            <div class="top-products-list component">
                <div class="product-listing">
                    <div class="sales-header sale">
                        <p class="sale-total">Total</p>
                        <p class="sale-customer">Customer</p>
                        <p class="sale-time">When</p>
                    </div>
                    <div class="sales-cont">
                        <?php render_recent_sales($recent_sales); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="js/overview.js"></script>

<?php include("footer.php"); ?>