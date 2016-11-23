<?php

/**
 * Routing File
 *
 * All requests to views are routed through this file
 */

namespace Marking;

use Marking\Controllers\Assignment;
use Marking\Controllers\Authentication;
use Marking\Controllers\Welcome;
use Marking\Controllers\Errors;
use Marking\Controllers\Admin;

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
    $uploadAssignment = new Assignment();
    $uploadAssignment->uploadFile();
}

else if ($uri == 'assignment') {
    $assignment = new Assignment();
    $assignment->loadAssignmentView();
}

else if ($uri == 'admin') {
    $admin = new Admin();
    $admin->adminView();
}

else {
    $errorController = new Errors();
    $errorController->notFound();
}


