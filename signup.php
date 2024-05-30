<?php 

include("header.php");

if(isset($_SESSION["userId"])) {
  header("location: /farmshop");
  exit();
}

$show = ''; $err_msg;

if(!empty($_GET)) {

  if(isset($_GET['error']) && !empty($_GET['error'])) {//error=emptyOrderInput, invalidName, invalidTotal, stmtfailed
    $show = 'show';

    if($_GET['error'] == 'emptyinput')      { $err_msg = 'Please fill out all fields.'; }
    if($_GET['error'] == 'invalidusername') { $err_msg = 'Username can only have letters and numbers.'; }
    if($_GET['error'] == 'invalidemail')    { $err_msg = 'Enter an email with proper format.'; }
    if($_GET['error'] == 'passwordmatch')   { $err_msg = 'Passwords must match to verify.'; }
    if($_GET['error'] == 'usernametaken')   { $err_msg = 'There is already an account with that username or email. If its you please try logging in.'; }

  } 

}

?>
<div class="push"></div>
<div class="login-cont">
  <div class="login-form signup-form">
    <div class="login-form-msg <?= $show ?>">
      <?= $err_msg ?>
    </div>
    <form action="includes/signup.inc.php" method="post">

      <div class="login-comp">
        <div class="input-comp full">
          <label for="name">Name </label><br>
          <input type="text" name="name" placeholder="Enter Full Name" required>
        </div>
      </div>

      <div class="login-comp">
        <div class="input-comp half">
          <label for="username">Username </label><br>
          <input type="text" name="username" placeholder="Enter Username" required>
        </div>
        <div class="input-comp half">
          <label for="email">Email </label><br>
          <input type="text" name="email" placeholder="Enter Email" required>
        </div>
      </div>
      
      <div class="login-comp">
        <div class="input-comp half">
          <label for="password">Password</label><br>
          <input type="password" name="password" placeholder="Enter Password" required>
        </div>
        <div class="input-comp half">
          <label for="repeat-password">Retype Password</label><br>
          <input type="password" name="repeatpassword" placeholder="Enter Password Again" required>
        </div>
      </div>
      
      <div class="login-comp">
        <input class="btn" type="submit" name="submit" id="submit" value="Sign Up">
      </div>
      
    </form>
    <div id="or">Have an account? Login below.</div>
    <a href="login"><button id="signup-btn">Log In</button></a>
    <p id="home"><a href="./">Home</a></p>
  </div>
</div>
<div class="push"></div>
<!--<div class="signup-form-cont">
  <h2 id="signup-title">Create an Account</h2>
  <form action="includes/signup.inc.php" method="post">
    <div class="signup-form">
      <label for="name">Name </label><br>
      <input type="text" name="name" placeholder="Enter Full Name" required>
    </div>
    <div class="signup-form">
      <label for="email">Email </label><br>
      <input type="text" name="email" placeholder="Enter Email" required>
    </div>
    <div class="signup-form">
      <label for="username">Username </label><br>
      <input type="text" name="username" placeholder="Enter Username" required>
    </div>
    <div class="signup-form">
      <label for="password">Password</label><br>
      <input type="password" name="password" placeholder="Enter Password" required>
    </div>
    <div class="signup-form">
      <label for="repeat-password">Repeat Password</label><br>
      <input type="password" name="repeatpassword" placeholder="Repeat Password" required>
    </div>
    <div id="signup-form-agreement">
      <input type="checkbox" required>
      <span>I agree to _blank_ Terms of Use and Privacy Policy</span>
    </div>
    <div class="signup-form">
      <input type="submit" name="submit" id="submit" value="Start Your Free Trial">
    </div>
  </form>  
</div>-->