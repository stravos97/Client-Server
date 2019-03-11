<?php
session_start();
unset($_SESSION['id']);
unset($_SESSION['hashedCampId']);
$page_title = 'Favourites';

$view = new stdClass();
require_once ('Models/FavouritesDataSet.php');
$id = $_GET['id'];

/**
* Fetches all the favourites based on idNumber
*
**/
$favouritesDataSet = new FavouritesDataSet();
$view->favouritesDataSet = $favouritesDataSet->fetchAllFavourites($id);
if (isset($_POST['deleteButton'])) {  //might need to change name or id in phtml
  echo $id_Favourites;
} else {
  echo "You have not deleted anything yet";
}
//var_dump($view->favouritesDataSet);

/**
* If the session search is set, the destroy it.
* This interferes with going back to the index page and searching again
*/

if (isset($_SESSION['search'])) {
  unset($_SESSION['search']);
}

require_once('Views/favourites.phtml');


  //cannot put php in phtml file e.g. index
