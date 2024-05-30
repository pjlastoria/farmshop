<?php 
include("header.php"); 
include("nav-bar.php"); 

if(empty($_SESSION['cart'])) {
    header("Location: ./"); // if cart is empty redirect home
}
?>

<div id="checkout-body">
    <h1 id="checkout-title">Checkout</h1>
    <div id="checkout-cont">
        <div class="order-comp info">
            <h3>Only Accepting Paypal for Now</h3>
            <div id="paypal-btn-cont"></div>
            <!--<h3>Your Info</h3>
            <div class="billing-section">
                <div class="checkout-form">
                    <div class="input-comp">
                        <label for="customer_name">Name</label><br>
                        <input type="text" name="customer_name" placeholder="Enter Full Name" required>
                    </div>
                    <div class="input-comp">
                        <label for="customer_email">Email </label><br>
                        <input type="text" name="customer_email" placeholder="Enter Email Address" required>
                    </div>
                </div>
            </div>
            <h3>Shipping</h3>
            <div class="billing-section">
                <div class="checkout-form">
                    <div class="input-comp full">
                        <label for="address">Address</label><br>
                        <input type="text" name="address" placeholder="Enter Address" required>
                    </div>
                </div>
                
                <div class="checkout-form">
                    <div class="input-comp">
                        <label for="zip_code">Zip Code </label><br>
                        <input type="text" name="zip_code" placeholder="Enter Zip Code" required>
                    </div>
                    <div class="input-comp">
                        <label for="city">City </label><br>
                        <input type="text" name="city" placeholder="Enter City" required>
                    </div>
                </div>
            </div>-->
        </div>
        <div class="order-comp summary">
            <h3>Summary</h3>
            <div class="checkout-items">

                <?php $cartTotal = checkoutSummary(); ?>

            </div>
            <div id="checkout-wrapper">
                <div id="checkout-text">
                    <ul id="left-list">
                        <li>Subtotal:</li>
                        <li>Delivery Fee:</li>
                        <li>Total:</li>
                    </ul>
                    <ul id="right-list">
                        <li>$<?php echo $cartTotal; ?></li>
                        <li>$2.99</li>
                        <li id="grand-total"><b>$<?php echo number_format($cartTotal + 2.99, 2, '.', ""); ?></b></li>
                    </ul>
                </div>
            </div>
            
        </div>
    </div>
</div>

<?php

//var_dump($_SESSION['cart']);

?>
                                                     


<!--<script src="js/nav-cart.js"></script>-->        

<script src="js/checkout.js" defer></script>