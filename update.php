<?php
session_start();

require_once('Models/CampDataSet.php');


$view = new stdClass();


$campDataSet = new CampDataSet();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  echo $id;
}

require_once('Views/update.phtml');
