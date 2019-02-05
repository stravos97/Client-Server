<?php //made by kevin skoglund
  ob_start(); //output buffering is turned on

  // Assign file paths to PHP constants
  // __FILE__ returns the current path to this file
  // dirname() returns the path to the parent directory
  define("ROOT_PATH", dirname(__FILE__));
  define("PROJECT_PATH", dirname(ROOT_PATH));
  define("VIEWS_PATH", PROJECT_PATH . '/Views');
  define("TEMPLATE_PATH", PROJECT_PATH . '/Views/template');

  // Assign the root URL to a PHP constant
  // * Do not need to include the domain
  // * Use same document root as webserver
  // * Can set a hardcoded value:
  // define("WWW_ROOT", '/~haashim/globe_bank/public');
  // define("WWW_ROOT", '');
  // * Can dynamically find everything in URL up to "/Views" then carry on after e.g. index.phtml. can't include html HAS TO BE PHP
  $public_end = strpos($_SERVER['SCRIPT_NAME'], '/Views') + 7;
  $doc_root = substr($_SERVER['SCRIPT_NAME'], 0, $public_end);
  define("WWW_ROOT", $doc_root);

  require_once('functions.php');
  //require_once('Models/db_credentials.php'); aldready included in database.php
  require_once ('Models/Database.php');



//  $object = new Database; //you connect to the dabase every time in dataset
//  $object->getdbConnection();

?>
