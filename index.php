<?php


namespace Marking;

use Marking\Controllers\Authentication;
use Marking\Controllers\Welcome;
use Marking\Controllers\Upload;
use Marking\Controllers\Errors;

require 'vendor/autoload.php';

session_start();

if (isset($_SERVER['REDIRECT_URL'])) {
    $uri = str_replace('/', '', $_SERVER['REDIRECT_URL']);
}

if ($uri == '') {
    header('location: /login');
    die();
}

else if ($uri == 'login') {
    $authentication = new Authentication();
    $authentication->login();
}

else if ($uri == 'welcome') {
    $accounts = new Welcome();
    $accounts->processWelcome();
}

else if ($uri == 'logout') {
    $authentication = new Authentication();
    $authentication->logout();
}

else if ($uri == 'register') {
    $authentication = new Authentication();
    $authentication->register();
}

else if ($uri == 'upload') {
    $upload = new Upload();
    $upload->uploadFile();
}

else {
    $errorController = new Errors();
    $errorController->notFound();
}


