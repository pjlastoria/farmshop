<?php 

include("header.php");

if(isset($_SESSION["userId"])) {
  header("location: ./");
  exit();
}

$show = ''; $msg;

if(!empty($_GET)) {

  if(isset($_GET['error']) && !empty($_GET['error'])) {//error=emptyOrderInput, invalidName, invalidTotal, stmtfailed
    $show = 'show';

    if($_GET['error'] == 'emptyinput')    { $msg = 'Please fill out form.'; }
    if($_GET['error'] == 'noSuchUser')    { $msg = 'Sorry no such user was found.'; }
    if($_GET['error'] == 'wrongpassword') { $msg = 'Entered the wrong password, please try again.'; }
    if($_GET['error'] == 'notLoggedIn')   { $msg = 'You must login as admin to access that page.'; }

  } 

  if(isset($_GET['result']) && !empty($_GET['result'])) {//error=emptyOrderInput, invalidName, invalidTotal, stmtfailed
    $show = 'show success';
    if($_GET['result'] == 'accountcreated')    { $msg = 'Account created, login below.'; }
  }

}

?>

<div class="push"></div>
<div class="login-cont">
  <div class="login-form">
    <div class="login-form-msg <?= $show ?>">
      <?= $msg ?>
    </div>
    <form action="includes/login.inc.php" method="post">
      
      <div class="login-comp">
        <div class="input-comp full">
          <label for="customer_name">Username</label><br>
          <input type="text" name="username" placeholder="Enter Username or Email" required>
        </div>
      </div>
      
      <div class="login-comp">
        <div class="input-comp full">
          <label for="password">Password</label><br>
          <input type="password" name="password" placeholder="Enter Password" required>
        </div>
      </div>
      
      <div class="login-comp">
        <input class="btn" type="submit" name="submit" id="submit" value="Log In">
      </div>
      
    </form>
    <div id="or">Or</div>
    <a href="signup"><button id="signup-btn">Sign Up</button></a>
    <p id="home"><a href="./">Home</a></p>
  </div>
</div>