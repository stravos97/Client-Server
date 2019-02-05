<?php
session_start();
$page_title = '404';

$view = new stdClass();

require_once('Views/404.phtml');


  //cannot put php in phtml file e.g. index
