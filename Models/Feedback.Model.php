<?php

namespace Marking\Models;

use Marking\Exceptions\CustomException;


/**
 * Class Marks
 *
 */
class Feedback extends Base
{

    public function setFeedback($studentId, $semester, $assignment, $feedback)
    {
        $result = $this->getAssignmentFeedback($studentId, $assignment, $semester);

        /**
         * Feedback already exists for this assignment
         * - Update it
         */
        if (isset($result[0])) {
            $this->updateFeedback($feedback, $result[0]['id']);
            return;
            die();
        }

        /**
         * Feedback does not exist for this assignment
         * - Create it
         */
        else {
            $this->writeFeedback($studentId, $semester, $assignment, $feedback);
        }
    }


    public function getAssignmentFeedback($studentId, $assignment, $semester)
    {
        $sql = "SELECT `id`, `feedback`  
                FROM `feedback` 
                WHERE student_id='$studentId' 
                AND semester='$semester'
                AND assignment_number='$assignment'
                ";

        $stm = $this->database->prepare(($sql), array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

        $stm->execute(array('$studentId, $assignment, $semester'));

        $data = $stm->fetchAll();

        return $data;
    }




}
