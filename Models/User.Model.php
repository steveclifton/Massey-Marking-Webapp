<?php

namespace Marking\Models;

use Marking\Controllers\MarkingConfig;
use PDO;

/**
 * Class User
 *
 * Class used to query the database for user data
 */
class User extends Base
{

    /**
     * Returns a students database records
     */
    public function getUserByStudentId($studentId)
    {
        $sql = "SELECT * FROM `users` WHERE student_id='$studentId' LIMIT 1 ";

        $stm = $this->database->prepare(($sql), array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

        $stm->execute(array('$studentId'));

        $data = $stm->fetchAll(PDO::FETCH_ASSOC);

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

    /**
     * Creates a new user in the database
     */
    public function create($userData)
    {
        $firstName = $userData['first_name'];
        $lastName = $userData['last_name'];
        $studentId = $userData['student_id'];
        $password = password_hash($userData['password'], PASSWORD_DEFAULT);
        $userType = $userData['user_type'];
        $semester = new MarkingConfig();
        $semester = $semester->getCurrentSemester();

        $sql = "INSERT INTO `users` 
                                (`id`, `first_name`, `last_name`, `user_type`, `student_id`, `semester`, `password`) 
                            VALUES 
                                (NULL, '$firstName', '$lastName', '$userType', '$studentId', '$semester', '$password');
               ";

        $stm = $this->database->prepare(($sql), array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

        $result = $stm->execute(array('$firstName, $lastName, $userType, $studentId, $semester, $password'));

        if (!$result) {
            return false;
        }
        return true;
    }

    public function removeExistingUser($studentId)
    {

        $sql = "DELETE FROM `users` WHERE `users`.`student_id` = '$studentId';";

        $stm = $this->database->prepare(($sql), array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

        $result = $stm->execute(array('$studentId'));

        if (!$result) {
            return false;
        }
        return true;
    }

    public function showAllUsers()
    {
        $sql = "SELECT first_name, last_name, student_id FROM `users`";

        $stm = $this->database->prepare(($sql), array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

        $stm->execute();

        $data = $stm->fetchAll(PDO::FETCH_ASSOC);

        return $data;
    }

}
