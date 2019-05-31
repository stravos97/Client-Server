<?php
session_start();
$view = new stdClass();

require_once ('Models/CampDataSet.php');

$campDataSet = (new CampDataSet());


/**
* The job of this class is just to suggest things
* It doesn't search and present search results, that is done in the controller
**/
// You can simulate a slow server with sleep
// sleep(2);

function is_ajax_request() {
  return isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
    $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
}

if(!is_ajax_request()) {
  exit;
}

function str_starts_with($choice, $query) {
  return strpos($choice, $query) === 0;
}


function str_contains($choice, $query) {
  return strpos($choice, $query) !== false;
}

// lowers all charachters and checks if any charachters of query are contained in choice
function search($query, $campsArray) {
  $matches = [];

  $d_query = strtolower($query);

  foreach($campsArray as $choice) {
    // Downcase both strings for case-insensitive search
    $d_choice = strtolower($choice);
    if(str_contains($d_choice, $d_query)) {
      $matches[] = $choice;
    }
  }

  return $matches;
}


//Ternary operator to see if the request was sent
$query = isset($_GET['searchTerm']) ? $_GET['searchTerm'] : '';

// find and return search suggestions as JSON
  $view->campDataSet = $campDataSet->getAllCountryName();

//  var_dump($view->campDataSet);
  $campsArray = $view->campDataSet;

  $suggestions = search($query, $campsArray);
//Find all the suggestions and return the top 5

// sorts the suggestions and ensures thare are a max of twow .
// this daat is passed back to the js page
  sort($suggestions);
  $max_suggestions = 2;
  $top_suggestions = array_slice($suggestions, 0, $max_suggestions);
//var_dump($campsArray);
  echo json_encode($top_suggestions);

 ?>
