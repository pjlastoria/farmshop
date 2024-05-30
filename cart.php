<?php

include("header.php");
include("nav-bar.php");
include("cart-modal.php");

if(isset($_POST['product_data'])) {


    $product_data = json_decode($_POST['product_data'], true);
    $key = $product_data['code'];

    if(isset($_POST['remove'])) {
        //$_SESSION['cart']['foo'] = 'bar';
        unset($_SESSION['cart'][$key]);
        return;
    }
    
    if(empty($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }



    $_SESSION['cart'][$key] = $product_data;

}

?>

<section id="cart-section">

    <div class="title-section">
        <h2 id="cart-section-title">Shopping Basket</h2>
        <p id="total-cart-items">Items in Basket (<span id="item-qty"><?php get_cart_qty(); ?></span>)</p>
    </div>

        <div id="cart-grid">
            <div id="cart-grid-wrapper">
                <?php $cartTotal = cart(); ?>
                <div id="empty-cart-cont">
                    <p class="cart-text" id="cart-empty">Looks like your basket is empty!</p>
                    <img src="images/cart-icon.svg" alt="basket">
                    <a class="cart-text" href="./">Browse Products</a>
                </div>
            </div>
            <div id="cart-grid-checkout-wrapper">
                <h2 id="cart-total">Subtotal: <?php echo $cartTotal; ?></h2>
                <a href="checkout">
                    <button type='submit' id="checkout-btn">Go to Checkout</button>
                </a>
            </div>
        </div>
</section>

<div class="push"></div>
<script src="js/nav-cart.js"></script>
<script src="js/cart.js"></script>

<?php include("footer.php") ?>