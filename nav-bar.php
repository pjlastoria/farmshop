<?php
$path = $_SERVER['REQUEST_URI'];
$page = basename($path,".php");
?>

<div id="top-nav">
  <div id="left">
    <a href="https://www.flaticon.com/free-icons/hand-apple" title="Logo">
      <img id="logo-img" class="top-nav-ele" src="images/farmshop_logo.svg">
    </a>
    <!--<div class="top-nav-ele" id="location">
      <img id="location-pin" class="top-nav-ele" src="images/location_pin.svg">
        Location
    </div>
    <form action="" method="post">
      <input class="top-nav-ele" id="search" type="text" name="keyword">
    </form>-->
  </div>

  <div id="right">
    <?php 
    if(isset($_SESSION["userId"])) {

      showUserNav($_SESSION["userName"]);

    } else { 
    ?>
    <div id="user-box" data-user="false" class="top-nav-ele">
      <span class="top-nav-user-ele"> <a href="signup">Signup</a> </span>
      <span class="top-nav-user-ele"> <a href="login">Login</a> </span>
    </div>

    <?php }
     
    if($page == 'checkout') { 
      //leave blank to not show nav cart on checkout page
          } else {?>
  
    <div id="nav-cart">
      <a href="cart"><img id="nav-cart-icon" src="images/cart-icon.svg" alt="">
      <p id="nav-cart-icon-quantity">
        <?= count($_SESSION['cart']);?>
      </p></a>
      <div id="nav-cart-cont">
        <div id="nav-cart-table-wrapper">
          <table>
          </table>
        </div>
        <p class="nav-cart-text" id="nav-cart-msg">Your Basket is Empty</p>
        <p class="nav-cart-text empty" id="nav-cart-total"></p>
        <a href="cart"><button class="modal-btn" id="nav-btn">Go to Cart</button></a>
      </div>
    </div>
    <?php } ?>
  </div>
</div>

<div id="nav-links-cont">
  <div id="top-nav-links">
    <a class="nav-links" href="./">Home</a>
    <!--<a class="nav-links" href="#">Products<p class="down-arrow"></p>
      <div class="nav-links-dropdown">
        <p class="dropdown-links">Trending</p>
        <p class="dropdown-links">Recommended</p>
        <p class="dropdown-links">Most Popular</p>
      </div>
    </a>
    <a class="nav-links" href="#">Checkout<p class="down-arrow"></p>
      <div class="nav-links-dropdown">
        <a href="cart.php"><p class="dropdown-links">Cart</p></a>
        <p class="dropdown-links">Add More</p>
        <p class="dropdown-links">Thank You</p>
      </div>
    </a>
    <a class="nav-links" href="#">Recommended<p class="down-arrow"></p>
      <div class="nav-links-dropdown">
        <p class="dropdown-links">Recent</p>
        <p class="dropdown-links">Based On History</p>
        <p class="dropdown-links" id="nav-login">Modal</p>
      </div>
    </a>-->
  </div>
</div>