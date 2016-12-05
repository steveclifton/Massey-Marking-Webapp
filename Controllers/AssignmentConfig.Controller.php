<?php


namespace Marking\Controllers;

use Exception;


/**
 * Class AssignmentConfig
 *
 * This class is designed to hold all assignment configurations
 *  Can be configured here and represented throughout the application
 *
 * - EG number of assignments is 7
 */
class AssignmentConfig extends Base
{
    private $numberOfAssignments = 7;

    /**
     * Returns the number of assignments
     * @return int
     */
    public function getAssignmentNumber()
    {
        return $this->numberOfAssignments;
    }

    public function getAssignmentCommands($assignmentNumber)
    {
        $file = file("/var/www/marking/AssignmentConfig/A$assignmentNumber" . ".txt");

        return $file;
    }


    /**
     * Copies the test files into the students Assignment folder
     *
     * - This is done when the student uploads the assignment so that if there are changes made to the assignment
     *    test files then they can be done in the master folder and will be applied anytime a student uploads
     *    their assignment to the marker.
     */
    public function copyTestFiles($aNum)
    {
        $studentId = $_SESSION['student_id'];
        system("sudo cp /home/assignmentfiles/A$aNum/*" . " /home/student/$studentId/A$aNum");
        return;
    }


}