<?php


namespace Marking;

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
    $authentication = new Controllers\Authentication();
    $authentication->login();
}

else if ($uri == 'login') {
    $authentication = new Controllers\Authentication();
    $authentication->login();
}

else if ($uri == 'welcome') {
    $accounts = new Controllers\Welcome();
    $accounts->processWelcome();
}

else if ($uri == 'logout') {
    $authentication = new Controllers\Authentication();
    $authentication->logout();
}

else if ($uri == 'register') {
    $authentication = new Controllers\Authentication();
    $authentication->register();
}

else {
    $errorController = new Controllers\Errors();
    $errorController->notFound();
}


