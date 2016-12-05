<?php

namespace Marking\Controllers;

/**
 *
 */
class Folders extends Base
{


    public function createFolders($studentId)
    {
        /**
         * Gets the number of Assignments specified
         */
        $assignmentNumber = new MarkingConfig();
        $assignmentNumber = $assignmentNumber->getAssignmentNumber();


        /**
         * If the directory does not exist for a student, create it
         *  - once created
         */
        if (!is_dir("/home/student/$studentId")) {
            system("sudo mkdir /home/student/$studentId");
            system("sudo chmod 777 /home/student/$studentId");
        } else {
            /* The folder (and student) already exists so all files are deleted out of its account */
            for ($i = 1; $i <= $assignmentNumber; $i++) {
                system("sudo rm /home/student/$studentId/A$i/*");
            }
        }

        /**
         * If the directory exists, create the assignment folder inside
         *  - Creates new folders
         *  - Copies assignment
         */
        if (is_dir("/home/student/" . $studentId)) {
            for ($i = 1; $i <= $assignmentNumber; $i++) {
                system("sudo mkdir /home/student/$studentId/A$i");
                system("sudo chmod 777 /home/student/$studentId/A$i");
            }
        }
    }
}