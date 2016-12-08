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






}
