<?php 

include("header.php"); 
include("../includes/functions.inc.php");
include("modal-order-form.php"); 

?>
<div id="admin-main">
    <div class="title-cont">
        <h1 id="admin-title" class="order-title">Orders</h1>
        <button class="order-btn small-screen-btn new-order">Add New Order</button>
    </div>
    <div class="orders-wrapper component">
        <div id="orders-cont">
            <div class="order-header order">
                <p class="order-id">Order ID</p>
                <p class="order-customer">Customer</p>
                <p class="order-total">Total</p>
                <p class="order-date">Date</p>
                <p class="order-method">Paid With</p>
                <p class="order-cart">Cart</p>
                <p class="order-action">Edit/Delete</p>
            </div>
            <div id="orders-list">
                <?php $order_count = get_orders(); ?>
            </div>

        </div>
        <div class="list-footer">
            <button class="order-btn wide-screen-btn new-order">Add New Order</button>
            <div class="pagination-cont">

                <div id="showing">
                    <span>Showing <span id="first-ele">1</span> to <span id="last-ele"><?php echo ($order_count > 8) ? 8 : $order_count; ?></span> of <span id="orders-count"><?php echo $order_count ?></span> Results</span>
                </div>
                <div class="page-btns">
                    <a href="#">&lsaquo;</a>
                    <a class="active-page" href="#">1</a>

                    <?php  $page_count = ($order_count/8 > 5) ? 5 : ceil($order_count/8);// to show the correct amount of page tabs based on order count
                        for($page = 2; $page <= $page_count; $page++) { ?>

                        <a href="#"><?php echo $page ?></a>

                    <?php } //&laquo;, &raquo;?>
                    
                    <a href="#">&rsaquo;</a>
                </div>
                
            </div>
        </div>
    </div>
</div>

<script src="js/order-crud.js"></script>
<?php include("footer.php"); ?>

