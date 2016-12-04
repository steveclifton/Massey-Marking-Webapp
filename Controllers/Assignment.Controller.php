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
            $target_dir = "/home/student/" . $_SESSION['student_id'] . "/" . "A" . $this->assignmentNumber;

            // Sets the target file
            $target_file = $target_dir . "/A$this->assignmentNumber.cpp";

            move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
            chmod($target_file, 0777);

        } catch (Exception $e) {
            var_dump($e); //TODO remove
            die();
        }

        if (!$this->compileAssignment($target_dir, $this->assignmentNumber)) {
            /* The assignment failed to compile */
            //die("Failed"); // TODO update this
        }


        $this->runAssignmentTests();

        /**
         * Now begin processing and parsing
         */




        header("location: /assignment?num=$this->assignmentNumber");
    }

    /**
     * Returns the assignment number the user is testing
     *
     */
    private function getAssignmentNumber($httpRef)
    {
        $number = explode('=', $httpRef);
        return $number[1];
    }


    /**
     * Compiles the assignment from a cpp file to a executable
     */
    private function compileAssignment($target_dir, $assNum)
    {
        try {
            chdir($target_dir);

            system("sudo g++ A$this->assignmentNumber.cpp -o A$this->assignmentNumber");
            
            if (file_exists($target_dir . "/A$assNum")) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            throw $e; //into the bin .. TODO
        }

    }


    /**
     * Uses the created executable to test a variety of input
     *  - Output from these tests is directed into a results text file
     */
    private function runAssignmentTests()
    {
        $studentId = $_SESSION['student_id'];

        $assignmentController = new AssignmentConfig();
        $cmd = $assignmentController->getAssignmentCommands($this->assignmentNumber);

        chdir("/home/student/$studentId/A$this->assignmentNumber");
        if ($this->assignmentNumber == 1) {
            for ($i = 1; $i <= $this->testNumber; $i++) {
                system($cmd[$i]);
                //system("timeout 10 ./A$this->assignmentNumber matrix$i.txt matrix$i.txt > result$i.txt");
                //chmod("result$i.txt", 0777); // don't need to change for read only
            }
        }
        die("DIED");

         if ($this->assignmentNumber == 2) {

        }
        else if ($this->assignmentNumber == 3) {

        }
        else if ($this->assignmentNumber == 4) {

        }
        else if ($this->assignmentNumber == 5) {

        }
        else if ($this->assignmentNumber == 6) {

        }
        else if ($this->assignmentNumber == 7) {

        }
        else if ($this->assignmentNumber == 8) {

        }

    }

}

