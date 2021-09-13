<?php

$requestUri = $_SERVER['REQUEST_URI'];
$requestUri = trim($requestUri, '/');
if ($requestUri && is_file($requestUri . '.php')) {
	include($requestUri . '.php');
	return;
}

error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../vendor/autoload.php';

$c = new PhpInfoController();
$htmlCode = $c->render();

echo $htmlCode;
