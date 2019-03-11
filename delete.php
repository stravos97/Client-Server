<?php
session_start();
unset($_SESSION['id']);
unset($_SESSION['hashedCampId']);
$page_title = 'Delete';

$view = new stdClass();

require_once('Views/Delete.phtml');
require_once ('Models/FavouritesDataSet.php');
$userID = $_GET['userID'];
$favouritesID = $_GET['favouritesID'];

/**
* If the commit button is pressed the dlete the favourite using the userID and the favouritesID
*/


if (isset($_POST['commit'])) {
  $favouritesDataSet = new FavouritesDataSet();
  $view->favouritesDataSet = $favouritesDataSet->deleteFavourite($userID, $favouritesID);
}
// if the logout button is pressed then destroy the session

if (isset($_POST['logOutButton'])) {
  session_destroy();
}
