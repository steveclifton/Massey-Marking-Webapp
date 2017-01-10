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

else if ($uri == 'adduser') {
    $authentication = new Admin();
    $authentication->addSingleUser();
}

else if ($uri == 'showcurrentstudents') {
    $admin = new Admin();
    $admin->showCurrentStudents();
}
else if ($uri == 'editstudents') {
    $admin = new Admin();
    $admin->editStudentsProfiles();
}


else if($uri == 'markingsetup') {
    $admin = new Admin();
    $admin->markingConfig();
}


else if ($uri == 'upload') {
    $uploadAssignment = new Assignment();
    $uploadAssignment->processUploadedFile();
}

else if ($uri == 'assignment') {
    $assignment = new Assignment();
    $assignment->loadAssignmentView();
}


else {
    $errorController = new Errors();
    $errorController->notFound();
}


