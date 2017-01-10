<?php


namespace Marking\Controllers;

use Marking\Models\AdminSetup;


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
class MarkingSetup extends Base
{
    private $setup;

    function __construct()
    {
        $this->setup = new AdminSetup();
    }


    /**
     * Returns the number of assignments
     */
    public function getAssignmentNumber()
    {
        return $this->setup->getNumberOfAssignments();
    }

    /**
     * Returns the current semester
     */
    public function getCurrentSemester()
    {
        return $this->setup->getCurrentSemester();
    }

    /**
     * Returns the number of test cases per assignment
     */
    public function getAssignmentTestNumber()
    {
        return $this->setup->getNumberOfTests();
    }

    /**
     * Returns a list of semesters
     */
    public function getListOfSemesters()
    {
        return $this->setup->getAllSemesters();
    }

    /**
     * Gets the commands from the assignments command-file
     */
    public function getAssignmentCommands($assignmentNumber)
    {
        $file = file("/var/www/marking/AssignmentConfig/A$assignmentNumber" . ".txt");

        return $file;
    }

    /**
     * Gets the compare commands file
     */
    public function getCompareCommands($num)
    {
        $file = file("/var/www/marking/AssignmentConfig/MasterCompare$num.txt");

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