<?php


namespace Marking\Controllers;


/**
 * Class AssignmentConfig
 *
 * This class is designed to hold all assignment configurations
 *  Can be configured here and represented throughout the application
 *
 * - Number of assignments is 7
 * - Number of test cases per assignment is 10
 * - Current Semester is 17-01
 */
class MarkingConfig extends Base
{
    private $numberOfAssignments = 7;
    private $numberOfAssignmentTestCases = 10;
    private $currentSemester = "17-01";


    /**
     * Returns the number of assignments
     */
    public function getAssignmentNumber()
    {
        return $this->numberOfAssignments;
    }

    /**
     * Returns the current semester
     */
    public function getCurrentSemester()
    {
        return $this->currentSemester;
    }

    /**
     * Returns the number of test cases per assignment
     */
    public function getAssignmentTestNumber()
    {
        return $this->numberOfAssignmentTestCases;
    }

    /**
     * Gets the commands from the assignments command-file
     */
    public function getAssignmentCommands($assignmentNumber)
    {
        $file = file("/var/www/marking/AssignmentConfig/A$assignmentNumber" . ".txt");

        return $file;
    }

    public function getCompareCommands()
    {
        $file = file("/var/www/marking/AssignmentConfig/MasterCompare.txt");

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