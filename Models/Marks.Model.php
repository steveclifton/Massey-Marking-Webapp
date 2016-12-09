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

    public function getUsersMarks($studentId, $assignmentNumber, $semester)
    {
        $sql = "SELECT mark 
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
            $this->setMark($studentId, $assignmentNumber, $semester, $mark);
        }
    }

}
