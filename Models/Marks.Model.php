<?php

namespace Marking\Models;

use Marking\Exceptions\CustomException;
use PDO;
use PDOException;

/**
 * Class Marks
 *
 */
class Marks extends Base
{

    /**
     * Returns a specific assignments mark from the Marks table
     */
    public function getUsersMark($studentId, $assignmentNumber, $semester)
    {
        $sql = "SELECT * 
                FROM `marks` 
                WHERE marks.student_id = '$studentId' 
                AND marks.semester = '$semester' 
                AND marks.assignment_number = '$assignmentNumber'
                ";

        $stm = $this->database->prepare(($sql), array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

        $stm->execute(array('$studentId, $assignmentNumber, $semester'));

        $data = $stm->fetchAll(PDO::FETCH_ASSOC);

        return $data;
    }

    /**
     * Returns all data from the Marks table
     */
    public function getAllUsersMarks($studentId, $semester)
    {
        $sql = "SELECT * 
                FROM `marks` 
                WHERE marks.student_id = '$studentId' 
                AND marks.semester = '$semester' 
                ORDER BY `assignment_number`
                ";

        $stm = $this->database->prepare(($sql), array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

        $stm->execute(array('$studentId, $semester'));

        $data = $stm->fetchAll(PDO::FETCH_ASSOC);

        return $data;
    }


    /**
     * Returns all of a users marks only
     */
    public function getOnlyUsersMarks($studentId, $semester)
    {
        $sql = "SELECT assignment_number, mark
                FROM `marks` 
                WHERE marks.student_id = '$studentId' 
                AND marks.semester = '$semester'
                ";

        $stm = $this->database->prepare(($sql), array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

        $stm->execute(array('$studentId, $semester'));

        $data = $stm->fetchAll(PDO::FETCH_ASSOC);

        return $data;
    }
    /**
     * Sets/Updates the users mark in the database
     */
    public function setUsersMark($studentId, $assignmentNumber, $semester, $mark)
    {
        $result = $this->getUsersMark($studentId, $assignmentNumber, $semester);

        /**
         * Mark already exists for this assignment
         * - Update it
         */
        if (isset($result[0])) {
            $this->updateMark($result[0]['id'], $mark);
        }

        /**
         * Mark does not exist for this assignment
         * - Create it
         */
        else {
            $id = $this->setMark($studentId, $assignmentNumber, $semester, $mark);
            return $id;
        }
    }

    /**
     * Inserts a new mark into the Marks table
     */
    private function setMark($studentId, $assignmentNumber, $semester, $mark)
    {
        $sql = "INSERT INTO `marks` (`id`, `student_id`, `mark`, `assignment_number`, `semester`, `created_date`) 
                VALUES (NULL, '$studentId', '$mark', '$assignmentNumber', '$semester', CURRENT_TIME())
                ";

        $stm = $this->database->prepare(($sql), array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

        $stm->execute(array('$studentId, $mark, $assignmentNumber, $semester'));
        $id = $this->database->lastInsertId();

        return $id;
    }

    /**
     * Updates an existing mark in the Marks table
     */
    private function updateMark($markId, $mark)
    {
        $sql = "UPDATE `marks` 
                SET `mark` = '$mark' 
                WHERE `marks`.`id` = '$markId'
                ";

        $stm = $this->database->prepare(($sql), array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

        $stm->execute(array('$mark, $markId'));

        return;
    }


}
