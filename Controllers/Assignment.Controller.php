<?php


namespace Marking\Controllers;

use Exception;

class Assignment extends Base
{
    private $assignmentNumber;
    private $testNumber = 10; // Set the number of tests to be run


    /**
     * Gets the users details from SESSION and passes to the view
     */
    public function loadAssignmentView()
    {
        $this->isNotLoggedIn();

        // Ensures that the user accesses a valid view
        if (!isset($_GET['num'])) {
            header('Location: /welcome');
        }

        /**
         * Query the database, check to see if there is any previous feedback for this assignment
         *
         * Dump into viewData if there is
         */

        $viewData = "";

        $this->render('Assignment', 'assignment' . $_GET['num'] . '.view', $viewData);
    }


    public function processUploadedFile()
    {
        // Gets the assignment number from the view
        $this->assignmentNumber = $this->getAssignmentNumber($_SERVER['HTTP_REFERER']);

        try {
            // Sets the target directory
            $target_dir = "/home/student/" . $_SESSION['student_id'] . "/" . $this->assignmentNumber;

            /**
             * If the target directory does not exist, CREATE it
             *  - otherwise, remove all files from it
             */
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777);
            } else {
                $this->removeAllFiles($target_dir);
            }

            $target_file = $target_dir . "/A$this->assignmentNumber.cpp";

            move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
            chmod($target_file, 0777);

        } catch (Exception $e) {
            var_dump($e); //TODO remove
            die();
        }


        if (!$this->compileAssignment()) {
            /* The assignment failed to compile */
            die("Failed"); // TODO update this
        }

        /* The Assignment compiled successfully */
        $this->copyInputFiles();

        $this->runAssignmentTests();

        /**
         * Now begin processing and parsing
         */




        header("location: /assignment?num=$this->assignmentNumber");
    }

    private function getAssignmentNumber($httpRef)
    {
        $number = explode('=', $httpRef);
        return $number[1];
    }

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

