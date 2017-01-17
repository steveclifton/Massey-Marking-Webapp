<?php

namespace Marking\Models;

use Marking\Controllers\MarkingSetup;
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
        $semester = new MarkingSetup();
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

    public function updateStudentDetails($userData)
    {
        $dbID = $userData['db_id'];
        $firstName = $userData['first_name'];
        $lastName = $userData['last_name'];
        $studentId = $userData['student_id'];
        if ($userData['password'] != '') {
            $password = password_hash($userData['password'], PASSWORD_DEFAULT);
        }

        $userType = $userData['user_type'];
        $semester = $userData['semester'];

        /**
         * This allows for the existing password to remain hashed and untouched.
         * - Only updated if it is attempting to be changed
         */
        if(isset($password)) {
            $sql = "UPDATE `users` 
              SET `first_name` = '$firstName',
                  `last_name` = '$lastName',
                  `user_type` = '$userType',
                  `student_id` = '$studentId',
                  `semester` = '$semester',
                  `password` = '$password'
              WHERE `users`.`id` = '$dbID'
        ";
        } else {
            $sql = "UPDATE `users` 
              SET `first_name` = '$firstName',
                  `last_name` = '$lastName',
                  `user_type` = '$userType',
                  `student_id` = '$studentId',
                  `semester` = '$semester'
              WHERE `users`.`id` = '$dbID'
        ";
        }


        $stm = $this->database->prepare(($sql), array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

        $result = $stm->execute(array('$firstName, $lastName, $userType, $studentId, $semester, $password'));

        return $studentId;
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

    public function getCurrentSemestersUsers($semester)
    {
        $sql = "SELECT id, first_name, last_name, student_id, semester
                FROM `users`
                WHERE `users`.`semester` = '$semester'
                ORDER BY `student_id`
                ";

        $stm = $this->database->prepare(($sql), array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

        $stm->execute(array('$semester'));

        $data = $stm->fetchAll(PDO::FETCH_ASSOC);

        return $data;
    }

    /**
     * Returns a students ID number based on their DB ID
     */
    public function getStudentID($dbID)
    {
        $sql = "SELECT student_id
                FROM `users`
                WHERE `users`.`id` = '$dbID'
                ";

        $stm = $this->database->prepare(($sql), array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

        $stm->execute(array('$dbID'));

        $data = $stm->fetchAll(PDO::FETCH_ASSOC);

        return $data[0];
    }
}
