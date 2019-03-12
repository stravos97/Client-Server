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



function search($query, $campsArray) {
  $matches = [];

  $d_query = strtolower($query);

  foreach($campsArray as $choice) {
    // Downcase both strings for case-insensitive search
    $d_choice = strtolower($choice);
    if(str_starts_with($d_choice, $d_query)) {
      $matches[] = $choice;
    }
  }

  return $matches;
}



$query = isset($_GET['searchTerm']) ? $_GET['searchTerm'] : '';

// find and return search suggestions as JSON

  $view->campDataSet = $campDataSet->getAllCountryName();

  $campsArray = $view->campDataSet;

  $suggestions = search($query, $campsArray);
//Find all the suggestions and return the top 5

  sort($suggestions);
  $max_suggestions = 10;
  $top_suggestions = array_slice($suggestions, 0, $max_suggestions);
//var_dump($campsArray);
  echo json_encode($top_suggestions);

 ?>
