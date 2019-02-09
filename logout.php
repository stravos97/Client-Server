<?php
session_start();

unset($_SESSION['userID']);
unset($_SESSION['ID-num']);
unset($_SESSION['id']);
unset($_SESSION['hashedCampId']);
session_destroy();
setcookie("test_cookie", "", time() - 3600);
// delete admin cookies
if (isset($_SERVER['HTTP_COOKIE'])) {
    $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
    foreach($cookies as $cookie) {
        $parts = explode('=', $cookie);
        $name = trim($parts[0]);
        setcookie($name, '', time()-1000);
        setcookie($name, '', time()-1000, '/');
    }
}
if (!isset($_SESSION['userID']) && !isset($_SESSION['ID-num'])){ //if session isn't set then redirect to index
header("Location: index.php");}
 ?>
