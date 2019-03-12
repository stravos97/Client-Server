<?php
session_start();
unset($_SESSION['id']);
unset($_SESSION['hashedCampId']);
$page_title = 'Welcome';

$view = new stdClass();
require_once('Views/index.phtml');
require_once ('Models/CampDataSet.php');
$searchText = '';
$searchTerm = '';

if (isset($_SESSION['search'])) {
  unset($_SESSION['search']);
}

$campDataSet = new CampDataSet();
$view->campDataSet = $campDataSet->search($searchText, '1');



if (isset($_POST['logOutButton'])) {
  session_destroy();
}

print_r($_COOKIE);



  //cannot put php in phtml file e.g. index
