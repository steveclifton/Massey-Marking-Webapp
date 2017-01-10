<?php

/**
 * Routing File
 *
 * All requests to views are routed through this file
 */

namespace Marking;

use Marking\Controllers\Assignment;
use Marking\Controllers\Authentication;
use Marking\Controllers\MarkingSetup;
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

else if ($uri == 'adduser') {
    $authentication = new Authentication();
    $authentication->addSingleUser();
}
else if ($uri == 'showcurrentstudents') {
    $admin = new Admin();
    $admin->showCurrentStudents();
}

else if ($uri == 'adminupload') {
    $admin = new Admin();
    $admin->uploadFile();
}

else if($uri == 'markingsetup') {
    $admin = new Admin();
    $admin->markingConfig();
}

else if ($uri == 'admincompile') {
    $admin = new Admin();
    $admin->compileFile();
}

else if ($uri == 'upload') {
    $uploadAssignment = new Assignment();
    $uploadAssignment->processUploadedFile();
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


