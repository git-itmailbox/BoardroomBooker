<?php
/**
 * Created by PhpStorm.
 * User: yura
 * Date: 06.02.17
 * Time: 21:51
 */
require_once('connection.php');
require_once('Auth.php');
require_once('models/users.php');
session_start();

$user = Auth::run();

if(!$user) {
    require_once('views/users/loginform.php');
    return;
}


if(isset($_SERVER["QUERY_STRING"]))
    $q = $_SERVER["QUERY_STRING"];
else
    $q = "";

$args = explode("/", $q);


if (isset($args[1]) && isset($args[2])) {
    $controller = $args[1];
    $action     = $args[2];
} else {
    $controller = 'mainpage';
    $action     = 'home';
}
require_once('routes.php');