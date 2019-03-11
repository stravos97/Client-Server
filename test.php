<?php
session_start();
unset($_SESSION['id']);
unset($_SESSION['hashedCampId']);
$view = new stdClass();
require_once ('Models/CampDataSet.php');

$page_title = 'Test';

$campDataSet = new CampDataSet();
$string = '';


//creates a varaible in the standard class ( in this case a field) that holds fwtch all camps
// /$campArray = $camp->fetchAllCamps(); //this saves all the information in an array with a variable which can be accessed

if (!isset($_POST['searchTerm'])) {
  $_POST['searchTerm'] = '';
}

if ($_POST['searchTerm'] != $string) {            //IF YOU don't do this then camp will just keep fetching all camps
  $view->campDataSet = $campDataSet->search($_POST['searchTerm'], $_GET['page']);
} else {
  $view->campDataSet = $campDataSet->fetchAllCamps($_GET['page']);
}

 //
//var_dump($view->campDataSet); //checks to see if an array is created


require_once('Views/test.phtml');
