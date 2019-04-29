<?php
session_start();
$view = new stdClass();
$view->pageTitle = 'Homepage';
require_once('Models/CampDataSet.php');
//require_once('Models/CampsiteDataSet.php');
$campsiteSites = new  CampDataSet();
$camsiteList = $campsiteSites->fetchAll(); //is too much data, need to limit it

//var_dump($camsiteList);


//you put locations[] outside the loop so that you get an array within an array, rsthether tahn just multiple arrays
  $locations = [];


foreach ($camsiteList as $key) {
   //echo $value->_date_opening_camp;
   $name = $key->getName_Camp();
   $lat = (float) $key->getLatitude();
   $long = (float) $key->getLongitude();
   $img = $key->getCamp_images();
   $id =  $key-> getID_Camps();



   //echo $key->getID_Camps();
     // code...
     $locations[] =
       [$name, $lat, $long, $id, $img];


}
   $view->locations = $locations;






//require_once('googlemapmultiplepinswithroute.phtml');

require_once('Views/maps.phtml');

 ?>
