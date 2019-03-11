<?php
session_start();
unset($_SESSION['id']);
unset($_SESSION['hashedCampId']);
$page_title = '404';

$view = new stdClass();

require_once('Views/404.phtml');


  //cannot put php in phtml file e.g. index
