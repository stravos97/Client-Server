<?php
session_start();
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


 /**
 * If the search button is pressed, helps search for an item
 */

if (isset($_POST['searchButton'])) {
  $searchTerm = $_POST['searchTerm']; //name of search text box
  // only show records that match the entered search term
  $view->campDataSet = ($campDataSet->search($searchTerm));

}
else {
  echo $searchTerm;
}

if (isset($_POST['logOutButton'])) {
  session_destroy();
}

print_r($_COOKIE);



  //cannot put php in phtml file e.g. index
