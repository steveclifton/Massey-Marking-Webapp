<?php

namespace Marking\Models;

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
     * Verifies whether the users student id and password exist and match
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
