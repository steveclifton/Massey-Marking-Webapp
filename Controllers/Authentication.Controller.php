<?php

namespace Marking\Controllers;

use Exception;
use Marking\Models\User;


class Authentication extends Base
{

    /**
     * Checks the users login credentials
     * - If authorised, redirect to account view
     */
    public function login()
    {
        $this->isLoggedIn();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = $_POST;

            $user = new User();
            $existingUser = $user->verify($data);

            if (isset($existingUser)) {
                $this->setSession($existingUser);
                header('location: /welcome');
                die();
            } else {
                header('location: /login');
                die();
            }
        }

        $this->render('Login Page', 'login.view');
    }

    /**
     * Destroys the SESSION and redirects to the login view
     */
    public function logout()
    {
        session_destroy();
        header('location: /login');
    }

    /**
     * Attempts to register a new user
     */
    public function addSingleUser()
    {
        //var_dump($_POST); die();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = $_POST;
            $user = new User();
            $userFolders = new Folders();

            try {
                /* Sets the user type */
                if (isset($data['user_type'])) {
                    $data['user_type'] = "admin";
                } else {
                    $data['user_type'] = "student";
                }

                /* If the user already exists, remove them from the DB */
                $checkStudent = $user->getUserByStudentId($data['student_id']);
                if (isset($checkStudent[0])) {
                    $user->removeExistingUser($data['student_id']);
                }
                /* Create user and folders */
                $user->create($data);
                $userFolders->createFolders($data['student_id']);

            } catch (Exception $e) {
                header('location: /');
            }
       }

       $this->render('Add New User', 'adduser.view');
    }

    /**
     * Sets the SESSION data
     *
     * @param $data
     */
    public function setSession ($data)
    {
        $_SESSION['id'] = $data['id'];
        $_SESSION['student_id'] = $data['student_id'];
        $_SESSION['first_name'] = $data['first_name'];
        $_SESSION['last_name'] = $data['last_name'];
        $_SESSION['user_type'] = $data['user_type'];
    }
    
}


