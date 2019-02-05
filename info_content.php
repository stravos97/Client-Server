<?php
session_start();
$page_title = 'Information';

$view = new stdClass();


require_once ('Models/CampDataSet.php');
require_once ('Models/CampRecordsDataSet.php');
require_once ('Models/FavouritesDataSet.php');
if (isset($_SESSION['search'])) {
  unset($_SESSION['search']);
}



$searchText = '';
$searchTerm = '';
$id = $_GET['id'];

$campDataSet = new CampDataSet();
$campRecordsDataSet = new CampRecordsDataSet();
$view->campDataSet = $campDataSet->search($searchText, $_GET['page']);

if (isset($_POST['searchButton'])) {
  $searchTerm = $_POST['searchTerm']; //name of search text box
  // only show records that match the entered search term
  $view->campDataSet = ($campDataSet->search($searchTerm, $_GET['page']));   //IT GOES IN TEST.PHP

}
else {
  //$view->campDataSet = $campDataSet->fetchAllCamps();
  echo $searchTerm;
}

$view->campRecordsDataSet = $campRecordsDataSet->fetchParagraph($id);

/**
* Adds an item to the favourites list, using the id of the page and the users, session id Number
*/

if (isset($_POST['addFavourites'])) {
  $favouritesDataSet = new FavouritesDataSet();
  $view->favouritesDataSet = $favouritesDataSet->insertIntoFavourites($id, $_SESSION['ID-num']);
}
require_once('Views/info_content.phtml'); //NEED TO REQUIRE THE VIEW AFTER ALL OTHER REQUIRES
