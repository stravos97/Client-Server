<?php
session_start();
require_once ('Models/CampDataSet.php');

class AutoSuggest{

  public function __construct() {
    self::steps();
  }


  public function objectCreation() {
    $view = new stdClass();
    $campDataSet = (new CampDataSet());

  }

  public function steps(){
    self::objectCreation();
    self::is_ajax_request();

    if(!is_ajax_request()) {
      exit;
    }

self::step3();


return echo json_encode($top_suggestions);
  }

  public function is_ajax_request() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
    $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
  }


public function step3() {
  $query = isset($_GET['searchTerm']) ? $_GET['searchTerm'] : '';

  // find and return search suggestions as JSON

    $view->campDataSet = $campDataSet->getAllCountryName();
  //  var_dump($view->campDataSet);
    $campsArray = $view->campDataSet;

    $suggestions = self::search($query, $campsArray);
  //Find all the suggestions and return the top 5

    self::sort($suggestions);
    $max_suggestions = 2;
    $top_suggestions = array_slice($suggestions, 0, $max_suggestions);
}

public function search($query, $campsArray) {
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

public function str_starts_with($choice, $query) {
  return strpos($choice, $query) === 0;
}


public function str_contains($choice, $query) {
  return strpos($choice, $query) !== false;
}

}




/**
* The job of this class is just to suggest things
* It doesn't search and present search results, that is done in the controller
**/
// You can simulate a slow server with sleep
// sleep(2);






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
