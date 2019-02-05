<?php
//NEED TO FIX
//Could also have phone number validation
session_start();
$page_title = 'Register';

$view = new stdClass();
require_once ('Models/UserAccountsDataSet.php');
$UserAccountsDataSet = new UserAccountsDataSet();

if (isset($_POST['signup-submit'])) {
  $areaCode = $_POST['aCode'];
  $phoneNumber = $_POST['number'];
  $email = $_POST['mail'];
  $password = $_POST['pwd'];
  $passwordRepeat = $_POST['pwd-repeat'];
}

if (isset($_SESSION['search'])) {
  unset($_SESSION['search']);
}

/*/
Checks to see if any of the email fields are emty and displays an error message in the url
Also validates the email addresses
Checjs to see if the passwords are both the same
Makes sure password contains:
            May contain letter and numbers
            Must contain at least 1 number and 1 letter
            May contain any of these characters: !@#$%
            Must be 8-12 characters
/*/
if (isset($_POST['signup-submit'])) {

  if (empty($areaCode) || empty($phoneNumber) || empty($email) || empty($password) || empty($passwordRepeat)) {
    header("Location: register.php?error=emptyFields&aCode=".$areaCode."number=".$phoneNumber."&mail=".$email);
  }
   elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) { // without the ! will filter right email
    header("Location: register.php?error=InvalidEmail&mail=".$email);
    exit();
  }
  elseif (!preg_match("/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{8,30}$/", $password)) {
    header("Location: register.php?error=PasswordWrongFormat");
    exit();
  }
  elseif (!preg_match("/^\+/", $areaCode)) { //CHECK
    header("Location: register.php?error=AreaCodeWrong");
    exit();
  }
  elseif ($password !== $passwordRepeat) { // without the ! will filter right email
   header("Location: register.php?error=PasswordsCheck&aCode=".$areaCode."number=".$phoneNumber."&mail=".$email);
   exit();
  }
  else {
    if (empty($view->UserAccountsDataSet = $UserAccountsDataSet->checkDetails($email))) {   //the email
      echo "ok";
      // register the user because no user was found    //NEED TO FIX THIS
      $view->UserAccountsDataSet = $UserAccountsDataSet->register($email, $password, $phoneNumber, $areaCode);
    } else {
        //that user is already regiserd
        header("Location: 404.php");
    }

  }

}



require_once('Views/register.phtml');


  //cannot put php in phtml file e.g. index
