<?php

namespace Marking\Models;

use Marking\Exceptions\CustomException;

use PDO;

/**
 * Class User
 *
 * Class used to query the database for user data
 *
 * @package
 */
class User extends Base
{

    public function getUserByStudentId($studentId)
    {
        $sql = "SELECT * FROM `users` WHERE student_id='$studentId' LIMIT 1 ";

        $stm = $this->database->prepare(($sql), array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

        $stm->execute(array('$studentId'));

        $data = $stm->fetchAll();

        return $data;
    }

    /**
     * Creates a new user if registration information is valid
     *
     * @param $userData
     * @return User - returns a new user object - used to log the user in automatically
     */
//    public function create($userData)
//    {
//
//        /* Sanitizes and filters data before being inserted */
//        $data['student_id'] = $this->database->real_escape_string($userData['username']);
//        $data['first_name'] = ucfirst($this->database->real_escape_string($userData['first_name']));
//        $data['last_name'] = ucfirst($this->database->real_escape_string($userData['last_name']));
//        $data['password'] = $this->database->real_escape_string($userData['password']);
//        $data['email'] = strtolower($this->database->real_escape_string($userData['email']));
//
//        $data['email'] = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
//
//        $password = password_hash($data['password'], PASSWORD_DEFAULT);
//
//        $this->database->query("INSERT INTO `users` (
//                                            `id`,
//                                            `student_id`,
//                                            `first_name`,
//                                            `last_name`,
//                                            `password`,
//                                            `email`,
//                                            `create_date`
//                                            ) VALUES (
//                                            NULL,
//                                             '".$data['student_id']."',
//                                             '".$data['first_name']."',
//                                             '".$data['last_name']."',
//                                             '".$password."',
//                                             '".$data['email']."',
//                                             CURRENT_TIMESTAMP
//                                             )"
//        );
//
//        $newUserData = $this->getUserByStudentId($data['student_id']);
//
//        $newUser = new User();
//
//        foreach ($newUserData as $key => $value) {
//            $newUser->$key = $value;
//        }
//
//        return $newUser;
//    }

    /**
     * Verifies whether the users student id and password exist and match
     *
     * @param $userData
     * @return mixed
     */
    public function verify($userData)
    {
        $existingUser = $this->getUserByStudentId($userData['student_id']);

        if (isset($existingUser)) {
            $existingUser = $existingUser['0'];
        }

        /* Checks the database to see whether passwords match, if they do, user details are returned */
        if (password_verify($userData['password'], $existingUser['password'])) {
            return $existingUser;
        } else {
            $_SESSION['failedLogin'] = "Please check login details";
        }
    }


}
