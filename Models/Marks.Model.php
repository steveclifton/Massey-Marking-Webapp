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

        $data = $stm->fetchAll();

        return $data;
    }



}
