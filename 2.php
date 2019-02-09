<?php
ini_set('session.cache_limiter','public');
ini_set("display_errors", 1);
session_cache_limiter(false);
session_start();
$page_title = 'Your search results';


$view = new stdClass();

require_once ('Models/CampDataSet.php');

$campDataSet = (new CampDataSet());
$search = (new CampDataSet());
$string = '';



if (!isset($_POST['searchTerm'])) {
  $_POST['searchTerm'] = '';
}

/**
* gets the search term from the index page and uses the campDataSet object to search
*/

if (isset($_POST['searchTerm']) || isset($_SESSION['search'])) {
    if ($_POST['searchTerm'] != $string) {            //IF YOU don't do this then camp will just keep fetching all camps
        $view->campDataSet = $campDataSet->search($_POST['searchTerm'], $_GET['page']);                              //IF YOU COMMENT THIS LINE OF CODE OUT FILTERS WORK PERFECTLY BUT SEARCH BREAKS
        $string = $_POST['searchTerm'];
        $_SESSION['search'] = $string;

    } elseif (isset($_SESSION['search']) && $_SESSION['search'] !== 'All countries') {
        $view->campDataSet = $campDataSet->search($_SESSION['search'], $_GET['page']);
        $string = $_SESSION['search'];

    } else {
        $view->campDataSet = $campDataSet->fetchAllCamps($_GET['page']);
        $string = "All countries";
    }
} else {

  if (isset($_GET['page'])) {
    # code...
     $view->campDataSet = $campDataSet->fetchAllCamps($_GET['page']);
  }
  $string = "All countries";
}


/**
* sets the string to a seesion so it isnt lost when filters are applied
*/

if (!isset($_SESSION['search'])) {
    $_SESSION['search'] = $string;
}


/**
*
* If the button is presset and any of filters are not empty, then use the iD number function to apply the filters
**/

  if (isset($_POST['submitButton'])) {
       //echo $_POST['shower'];
       if (isset($_POST['shower'])) {
         $a = $_POST['shower'];
       }else {
        $a = '';
       }
       if (isset($_POST['wifi'])) {
         $b = $_POST['wifi'];
       } else {
         $b = '';
       }
       if (isset($_POST['disabled'])) {
         $c = $_POST['disabled'];
       } else {
         $c = '';
       }
       if (isset($_POST['laundry'])) {
         $d = $_POST['laundry'];
       } else {
         $d = '';
       }

       if (!isset($_SESSION['search'])) { //WHY DOES THIS WORK if the seesion isn't set? It should search for all ID numbers, if the session isn't set
         $view->search = $search->allIdNumbers($a, $b, $c, $d, $_GET['page']);
     } else {

       if ($_SESSION['search'] === 'All countries') {
         $view->search = $search->allIdNumbers($a, $b, $c, $d, $_GET['page']);
       } else {

         $view->search = $search->idNumbers($a, $b, $c, $d, $_SESSION['search'], $_GET['page']);
       }

     }

  }
var_dump($_SESSION['search']);


require_once('Views/2.phtml');



  //cannot put php in phtml file e.g. index
