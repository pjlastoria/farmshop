<?php

if(isset($_POST['submit'])) {
  
  $name = $_POST['name'];
  $email = $_POST['email'];
  $username = $_POST['username'];
  $password = $_POST['password'];
  $repeatPassword = $_POST['repeatpassword'];

  require_once 'dbh.inc.php';
  require_once 'functions.inc.php';
 
  //check if signup input is empty, more thorough than setting it equal to true
  if(emptyInputSignup($name, $email, $username, $password, $repeatPassword) !== false) {
    header("location: ../signup.php?error=emptyinput");
    exit();
  }
  if(invalidUsername($username) !== false) {
    header("location: ../signup.php?error=invalidusername");
    exit();
  }
  if(invalidEmail($email) !== false) {
    header("location: ../signup.php?error=invalidemail");
    exit();
  }
  if(passwordMatch($password, $repeatPassword) !== false) {
    header("location: ../signup.php?error=passwordmatch");
    exit();
  }
  if(usernameOrEmailExists($conn, $username, $email) !== false) {
    header("location: ../signup.php?error=usernametaken");
    exit();
  }
  
  createUser($conn, $name, $email, $username, $password, $repeatPassword);
  

} else {
  header("location: ../signup");
  exit();
}

?>