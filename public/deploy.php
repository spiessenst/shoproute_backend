<?php
error_reporting( E_ALL );
ini_set( 'display_errors', 1 );

header('Access-Control-Allow-Credentials: true');

use App\Kernel;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\ErrorHandler\Debug;
use Symfony\Component\HttpFoundation\Request;

//FOR PRODUCTION
$install_path = "/eindwerk";
$vendor_folder_parent = "/system";

//CHANGED PATH FOR PRODUCTION
require dirname(__DIR__). $install_path . $vendor_folder_parent . "/vendor/autoload.php";

//CHANGED PATH FOR PRODUCTION
(new Dotenv())->bootEnv(dirname(__DIR__). $install_path . '/.env');


if ($_SERVER['APP_DEBUG']) {
    umask(0000);

    Debug::enable();
}

$kernel = new Kernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG']);
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);