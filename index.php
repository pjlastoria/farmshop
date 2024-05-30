<?php 

include("header.php");
include("nav-bar.php"); 
include("notadmin-msg.php"); 
?>



<div id="hero">
  <div id="bg-img">
    <div id="hero-block">
      <div class="hero-half" id="left-side">
        <h1>We help local farmers sell directly to their customers</h1>
        <p id="hero-description">Buy directly from farmers, help the environment, and save money.</p>
      </div>
      <div class="hero-half" id="right-side">
        <div id="hero-right-container">
          <div id="hero-deal">
            <h2 id="hero-headline">Fresh this Week</h2>
            <div id="sale-item-cont">
              <div class="sale-item">
                <div class="sale-item-wrapper">
                  <div class="sale-item-img">
                    <img src="https://source.unsplash.com/ZugQ-1NBaO0/100x100" alt="celery">
                  </div>
                  <ul>
                    <li class="sale-item-price">$1.99</li>
                    <li class="sale-item-name">Celery</li>
                  </ul>
                </div>
              </div>
              <div class="sale-item">
                <div class="sale-item-wrapper">
                  <div class="sale-item-img">
                    <img src="https://source.unsplash.com/I58f47LRQYM/100x100" alt="apples">
                  </div>
                  <ul>
                    <li class="sale-item-price">$2.99</li>
                    <li class="sale-item-name">Apples</li>
                  </ul>
                </div>
              </div>
              <div class="sale-item">
                <div class="sale-item-wrapper">
                  <div class="sale-item-img">
                    <img src="https://source.unsplash.com/7tKj0_Nf6GU/100x100" alt="oranges">
                  </div>
                  <ul>
                    <li class="sale-item-price">$2.99</li>
                    <li class="sale-item-name">Oranges</li>
                  </ul>
                </div>
              </div>
              <div class="sale-item">
                <div class="sale-item-wrapper">
                  <div class="sale-item-img">
                    <img src="https://source.unsplash.com/eFFnKMiDMGc/100x100" alt="carrots">
                  </div>
                  <ul>
                    <li class="sale-item-price">$1.99</li>
                    <li class="sale-item-name">Carrots</li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
          <!--<form action="" method="" class="form-example">
            <div class="hero-form">
              <label for="first-name">First Name </label><br>
              <input type="text" name="first_name" required>
            </div>
            <div class="hero-form">
              <label for="last-name">Last Name </label><br>
              <input type="text" name="last_name" required>
            </div>
            <div class="hero-form">
              <label for="email">Email </label><br>
              <input type="email" name="email" required>
            </div>
            <div class="hero-form">
              <input type="submit" id="submit" value="Start Your Free Trial">
            </div>
          </form>-->
        </div>
      </div>
    </div>
    <img src="images/hero-bg.jpg" />
  </div>
</div> 

<div id="perk-cont">
  <div class="perk">
    <img src="images/delivery_truck.svg" alt="delivery truck" class="perk-icon">
    <p class="perk-description">a lacus vestibulum. A iaculis at erat pellentesque adipiscing commodo elit at. Ullamcorper</p>
  </div>
  <div class="perk">
    <img src="images/recycle_bin.svg" alt="recycle bin" class="perk-icon">
    <p class="perk-description">Nunc consequat interdum varius sit amet mattis vulputate enim. Aenean euismod elementum nisi quis eleifend quam adipiscing vitae proin.</p>
  </div>
  <div class="perk">
    <img src="images/dollar_sign.svg" alt="dollar sign" class="perk-icon">
    <p class="perk-description">sit amet venenatis urna cursus eget. Eget egestas purus viverra accumsan in nisl nisi.</p>
  </div>
</div>

<?php include("product-sections.php") ?>

<script src="js/main.js"></script>
<script src="js/carousel.js"></script>
<script src="js/nav-cart.js"></script>

<?php include("footer.php") ?>