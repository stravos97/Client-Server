<?php
session_start();
$page_title = 'Information';

$view = new stdClass();


require_once ('Models/CampDataSet.php');
require_once ('Models/CampRecordsDataSet.php');
require_once ('Models/FavouritesDataSet.php');
require_once ('Models/RatingDataSet.php');


/*
 If the id hashes match then tih is true
*/
if(password_verify($_SESSION['campId'], $_SESSION['hashedCampId'])) {
    // If the password inputs matched the hashed password in the database
    $id = $_SESSION['campId'];

} else {
  header("Location: 404.php");
}

/*
* Unsets the search session
*/
if (isset($_SESSION['search'])) {
  unset($_SESSION['search']);
}



$searchText = '';
$searchTerm = '';


$campDataSet = new CampDataSet();
$campRecordsDataSet = new CampRecordsDataSet();
$ratingDataSet = new RatingDataSet();

$view->campDataSet = $campDataSet->search($searchText, '1');

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

if (isset($_POST['starRating'])) {
//print_r($_POST);


    foreach ($_POST as $value) {
        echo $value;

        $view->ratingDataSet = $ratingDataSet->addRating($_SESSION['userID'], $value);
    }
}

/**
* Adds an item to the favourites list, using the id of the page and the users, session id Number
*/

if (isset($_POST['addFavourites'])) {
  $favouritesDataSet = new FavouritesDataSet();
  $view->favouritesDataSet = $favouritesDataSet->insertIntoFavourites($id, $_SESSION['ID-num']);
}
require_once('Views/info_content.phtml'); //NEED TO REQUIRE THE VIEW AFTER ALL OTHER REQUIRES
