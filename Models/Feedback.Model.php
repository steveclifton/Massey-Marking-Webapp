<?php

namespace Marking\Models;

use Marking\Exceptions\CustomException;
use Marking\Controllers\MarkingConfig;
use PDO;

/**
 * Class Marks
 *
 */
class Feedback extends Base
{

    /**
     * Returns all from the Feedback and Marks table (joined)
     */
    public function getMarkAndFeedback($studentId, $semester)
    {
        $sql = "SELECT *  
                FROM `feedback` 
                JOIN `marks`
                ON feedback.mark_id = marks.id
                WHERE feedback.student_id='$studentId' 
                AND feedback.semester='$semester'
                ";

        $stm = $this->database->prepare(($sql), array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

        $stm->execute(array('$studentId, $semester'));

        $data = $stm->fetchAll(PDO::FETCH_ASSOC);

        return $data;
    }

    /**
     * Returns the ID and Feedback from the Feedback table
     */
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

        $data = $stm->fetchAll(PDO::FETCH_ASSOC);

        return $data;
    }

    /**
     * Sets/Updates the users feedback in the database
     */
    public function setUserFeedback($studentId, $semester, $assignment, $feedback)
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
            $this->setFeedback($studentId, $semester, $assignment, $feedback);
        }
    }

    /**
     * Inserts new feedback into the Feedback table
     */
    private function setFeedback($studentId, $semester, $assignment, $feedback)
    {
        $sql = "INSERT INTO `feedback` (`id`, `student_id`, `assignment_number`, `semester`, `feedback`, `created_date`) 
                VALUES (NULL, '$studentId', '$assignment', '$semester', '$feedback', CURRENT_TIME());";

        $stm = $this->database->prepare(($sql), array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

        $stm->execute(array('$studentId, $assignment, $semester, $feedback'));

        return;
    }

    /**
     * Updates feedback in the Feedback table
     */
    private function updateFeedback($feedback, $feedbackId)
    {
        $sql = "UPDATE `feedback` 
                SET `feedback` = '$feedback' 
                WHERE `feedback`.`id` = '$feedbackId'
                ";

        $stm = $this->database->prepare(($sql), array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

        $stm->execute(array('$feedback, $feedbackId'));

        return;
    }

}
