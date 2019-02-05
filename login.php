<?php
session_start();
$page_title = 'Login';

$view = new stdClass();
require_once ('Models/UserAccountsDataSet.php');
$UserAccountsDataSet = new UserAccountsDataSet();

/**
* If the login button is pressed then sets the input email and passwrods as variables
**/

if (isset($_POST['login-submit'])) {
  $inputEmail = $_POST['inputEmail'];
  $inputPassword = $_POST['inputPassword'];
}

if (isset($_SESSION['search'])) {
  unset($_SESSION['search']);
}



/**
* If the login button is pressed then validate the email and check to see if any fields are empty
*/

if (isset($_POST['login-submit'])) {

  if (empty($inputEmail) || empty($inputPassword) ) {
    header("Location: login.php?error=Cant_Find_Email&inputEmail=".$inputEmail);
  }
   elseif (!filter_var($inputEmail, FILTER_VALIDATE_EMAIL)) {
    header("Location: login.php?error=Cant_Find_Email&inputEmail=".$inputEmail);
    exit();
  }elseif ($_POST['captcha'] != $_SESSION['captcha_code']) {
      header("Location:login.php?error=CAPTCHA_WRONG");
  } else {
      $view->UserAccountsDataSet = $UserAccountsDataSet->login($inputEmail, $inputPassword);


  }

}

/**
* Checks to see if the session is set in the CampRecords DataSet and logs you in
* Also redirects you after you have successfully logged in
**/
if (isset($_SESSION['userID'])) {
  echo "You have loggeed in";

  if (isset($_POST['login-submit'])){
      header("Location:index.php");

  }



} else {
  echo "You have not logged in";
}

// if (isset($_POST['captcha-submit'])) {
// //  echo $_POST['captcha'];
//
//   if($_POST['captcha'] != $_SESSION['digit']) {
//   //  die("Sorry, the CAPTCHA code entered was incorrect!");
//       header("Location:login.php?error=CAPTCHA_WRONG");
//   }
// }


require_once('Views/login.phtml');


  //cannot put php in phtml file e.g. index
