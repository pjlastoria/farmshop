<?php

$show;

if(!empty($_GET) && $_GET['error'] == 'notAdmin') {

  $show = 'appear';

}
?>

<div class="modal-bg <?= $show ?>">
  <div class="modal">

    <div id="modal-msg-empty">
      <p id="modal-msg">Hmmm, your cart looks empty, please add products before checkout.</p>
      <div id="modal-btns">
        <a href="#">        <button class="modal-btn">Stay</button></a>
        <a href="./"><button class="modal-btn">Browse Products</button></a>
      </div>
    </div>

    <div id="modal-msg-guest">
      <p id="modal-msg">You are not signed in. You may sign in and purchase or create an account if you don't have one. Or continue as guest.</p>
      <div id="modal-btns">
        <a href="checkout"><button class="modal-btn">Continue as Guest</button></a>
        <a href="login">   <button class="modal-btn">Login/Signup</button></a>
      </div>
    </div>
  </div>
</div>