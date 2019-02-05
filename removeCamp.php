<?php


require_once('Models/CampDataSet.php');


$view = new stdClass();


$campDataSet = new CampDataSet();
if (isset($_GET['id'])) {
  echo $_GET['id'];
  $view->campDataSet = $campDataSet->removeCamp($_GET['id']);
} else {
  echo "It failed because the ID isn't set";
}


require_once('Views/removeCamp.phtml');
