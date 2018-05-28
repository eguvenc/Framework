<?php

// Define the root
define('ROOT', dirname(__DIR__));

// Prevent session cookies
ini_set('session.use_cookies', 0);

// Enable Composer autoloader
$autoloader = require ROOT . '/vendor/autoload.php';

define('APP', 'App');

use Dotenv\Dotenv;
if (false == isset($_SERVER['APP_ENV'])) {
    (new Dotenv(ROOT))->load();
}

// require dirname(__FILE__) . '/getallheaders.php';
// 
// Register test classes
$autoloader->addPsr4('Tests\\', 'tests/');