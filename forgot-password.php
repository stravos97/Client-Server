<?php
session_start();
unset($_SESSION['id']);
unset($_SESSION['hashedCampId']);
$page_title = 'Forgot Password';

$view = new stdClass();
if (isset($_SESSION['search'])) {
  unset($_SESSION['search']);
}


require_once('Views/forgot-password.phtml');


  //cannot put php in phtml file e.g. index
