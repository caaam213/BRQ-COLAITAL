<?php
session_start();
?>
<?php


$configErrors = require 'config/config_errors.php';
$config = require 'config/config.php';

// Séparer l'URL en plusieurs parties
$request = $_SERVER['REQUEST_URI'];
$request = ltrim($request, '/');
$request_parts = explode('/', $request);


// Déterminer le controller et la méthode à appeler
$i = 1;
if ($request_parts[$i] === 'index.php') {
    $i++;
}

$controller = !empty($request_parts[$i]) ? $request_parts[$i] : 'login';

if ($controller === 'index.php') {
    $i++;
    $controller = !empty($request_parts[$i]) ? $request_parts[$i] : 'login';
}

$i++;
$methode = !empty($request_parts[$i]) ? $request_parts[$i] : 'default';
$methode = explode('?', $methode)[0];

$controller_path = "controllers/" . $controller . "Controller.php";


// Si le controller existe, on l'instancie et on appelle la méthode
if (file_exists($controller_path)) {
    require_once $controller_path;

    $controller_name = ucfirst($controller) . "Controller";
    $controller_obj = new $controller_name();

    if (method_exists($controller_obj, $methode)) {
        $controller_obj->$methode();
    } else {
        $_SESSION['errorPage'] = $configErrors['404']; 
        header('Location: '.$config["base_url"].'index.php/error');  
    }
} else {
    $_SESSION['errorPage'] = $configErrors['404']; 
    header('Location: '.$config["base_url"].'index.php/error');  
}

    

?>
