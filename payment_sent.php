<?php
include('header.php');

session_unset();
session_destroy();

?>

<div class="login-cont">
    <div class="login-form">
        <h3>Payment Successful!</h3>
        <p>
            Thank you for your payment. 
            Your transaction has been completed. 
            Log into your PayPal account to view transaction details.
        </p>

        <a href="./">Go to Homepage</a>
    </div>
</div>