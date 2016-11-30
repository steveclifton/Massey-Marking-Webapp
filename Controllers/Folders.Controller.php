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
         * If the directory does not exist for a student, create it
         *  - once created
         */
        if (!is_dir("/home/student/" . $studentId)) {
            system("sudo mkdir /home/student/" . $studentId);
            system("sudo chmod 777 /home/student/" . $studentId);
        }

        /**
         * If the directory exists, create the assignment folder inside
         */
        if (is_dir("/home/student/" . $studentId)) {
            for ($i = 1; $i <= 8; $i++) {
                system("sudo mkdir /home/student/" . $studentId . "/A" . $i);
                system("sudo chmod 777 /home/student/" . $studentId . "/A" . $i);
            }
        }

        //Todo remove
        var_dump($studentId); die();
    }



    /**
     * Removes all the files in a directory
     */
    private function removeAllFiles($target_dir)
    {
        $files = glob($target_dir . "/*"); // get all file names

        // Removes each file in the directory
        foreach ($files as $file){
            if (is_file($file))
                unlink($file); // delete file
        }
        return true;
    }


}