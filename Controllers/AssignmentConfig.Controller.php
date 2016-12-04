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


}