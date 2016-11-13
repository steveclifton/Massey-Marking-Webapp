<?php

namespace Marking\Models;

use Marking\Exceptions\CustomException;


/**
 * Class User
 *
 * Class used to query the database for user data
 *
 * @package
 */
class User extends Base
{

    public function getUserByUsername($username)
    {

        $query = $this->database->query("SELECT *
                                         FROM 
                                         `users` 
                                         WHERE 
                                         username='$username'
                                         LIMIT 1
                                         "
        );

        $data = $query->fetch_assoc();

        return $data;
    }

    /**
     * Creates a new user if registration information is valid
     *  - logs them in to the internet banking system automatically
     *
     * @param $userData
     * @return User - returns a new user object - used to log the user in automatically
     * @throws ToolException
     */
    public function create($userData)
    {

        /* Sanitizes and filters data before being inserted */
        $data['first_name'] = ucfirst($this->database->real_escape_string($userData['first_name']));
        $data['last_name'] = ucfirst($this->database->real_escape_string($userData['last_name']));
        $data['username'] = strtolower($this->database->real_escape_string($userData['username']));
        $data['password'] = $this->database->real_escape_string($userData['password']);
        $data['email'] = strtolower($this->database->real_escape_string($userData['email']));

        $data['email'] = filter_var($data['email'], FILTER_SANITIZE_EMAIL);

        $password = password_hash($data['password'], PASSWORD_DEFAULT);

        $this->database->query("INSERT INTO `users` (
                                            `id`,
                                            `first_name`,
                                            `last_name`,
                                            `username`,
                                            `email`,
                                            `password`,
                                            `create_date`
                                            ) VALUES (
                                            NULL,
                                             '".$data['first_name']."', 
                                             '".$data['last_name']."', 
                                             '".$data['username']."', 
                                             '".$data['email']."', 
                                             '".$password."', 
                                             CURRENT_TIMESTAMP
                                             )"
        );

        $newUserData = $this->getUserByUsername($data['username']);

        $newUser = new User();

        foreach ($newUserData as $key => $value) {
            $newUser->$key = $value;
        }

        return $newUser;
    }

    /**
     * Verifies whether the users login email and password exist/match
     *
     * @param $userData
     * @return mixed
     * @throws ToolException
     */
    public function verify($userData)
    {
        $data['username'] = strtolower($this->database->real_escape_string($userData['username']));
        $data['password'] = $this->database->real_escape_string($userData['password']);


        $existingUser = $this->getUserByUsername($data['username']);

        /* Checks the database to see whether passwords match, if they do, user details are returned */
        if (password_verify($data['password'], $existingUser['password'])) {
            return $existingUser;
        }
    }

    public function isUsernameAvailable($userName)
    {
        $query = $this->database->query("SELECT * FROM `users` WHERE users.username LIKE '$userName'");

        if ($query->num_rows == 0) {
            return true;
        } else {
            return false;
        }
    }

    public function isEmailAvailable($email)
    {
        $query = $this->database->query("SELECT * FROM `users` WHERE users.email LIKE '$email'");

        if ($query->num_rows == 0) {
            return true;
        } else {
            return false;
        }
    }



}
