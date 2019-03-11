<?php
session_start();
unset($_SESSION['id']);
unset($_SESSION['hashedCampId']);
$page_title = 'Add Camps';

$view = new stdClass();
require_once ('Models/CampDataSet.php');
  $add = new CampDataSet();




  if (!isset($_POST['shower'])) {
    $_POST['shower']='0';
  }
  if (!isset($_POST['wifi'])) {
    $_POST['wifi']='0';
  }
  if (!isset($_POST['disabled'])) {
    $_POST['disabled']='0';
  }
  if (!isset($_POST['laundry'])) {
    $_POST['laundry']='0';
  }
/**
* Checks to see if the button was sumbitted
* It then cehcks to see if all the fields are in the right format (not empty, right syntax for lat, long)
* If all of this is correct  then inserts info into CampData and CampRecordsData
**/
if ($_SERVER['REQUEST_METHOD'] === 'POST') { //error could  be can't set id number
  $a = $_POST['name'];
  $b = $_POST['date'];
  $c = $_POST['time'];
  $d = $_POST['city'];
  $e = $_POST['country'];
  $f = $_POST['latitude'];
  $g = $_POST['longitude'];
  $h = $_POST['image'];
  $i = $_POST['block'];

  $j = $_POST['shower'];
  $k = $_POST['wifi'];
  $l = $_POST['disabled'];
  $m = $_POST['laundry'];


  if (empty($a) || empty($b) || empty($c) || empty($d) || empty($e) || empty($f) || empty($g) || empty($h) || empty($i)) {
    header("Location: addCamp.php.php?error=emptyFields");
  }
  else {
    $view->add = ($add->addCamp($a, $b, $c, $d, $e, $f, $g, $h, $i, $j,$k,$l,$m));
  }



}
require_once('Views/addCamp.phtml');
