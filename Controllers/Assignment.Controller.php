<?php


namespace Marking\Controllers;

use Exception;
use Marking\Models\Feedback;
use Marking\Models\Marks;

class Assignment extends Base
{
    private $assignmentNumber;
    private $semester;
    private $studentId;

    public function __construct()
    {
        $mSemester = new MarkingConfig();
        $this->semester = $mSemester->getCurrentSemester();

        $this->studentId = $_SESSION['student_id'];
    }


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
         * Gets the students current assignment mark
         */
        $mark = $this->getCurrentMark();
        if (isset($mark[0])) {
            $viewData['mark'] = $mark[0]['mark'];
        } else {
            $viewData['mark'] = 0;
        }

        /**
         * Gets the students current assignment feedback
         */
        $feedback = $this->getAssignmentFeedback();
        if (isset($feedback[0])) {
            $viewData['feedback'] = $feedback[0]['feedback'];
        } else {
            $viewData['feedback'] = "";
        }


        $this->render('Assignment', 'assignment.view', $viewData);
    }


    /**
     * This is the main function for Assignment Control
     *
     */
    public function processUploadedFile()
    {
        $feedback = new Feedback();
        $mark = new Marks();
        $this->assignmentNumber = $this->getAssignmentNumber($_SERVER['HTTP_REFERER']);

        $target_dir = "/home/student/" . $_SESSION['student_id'] . "/A$this->assignmentNumber";

        /**
         * Upload the file to the target directory
         */
        try {
            $this->uploadFile();
        } catch (Exception $e) {
            var_dump($e);
            die();
        }


        /**
         * Tries to compile the assignment
         * - If does not compile
         *      - updates DB and returns to page
         *
         * - Else
         *   Tries to run all the test cases on the assignment
         *      - If an infinite loop occurs, updates DB and returns to page
         */
        if (!$this->compileAssignment($target_dir, $this->assignmentNumber)) {
            $markId = $mark->setUsersMark($this->studentId, $this->assignmentNumber, $this->semester, 0);
            $feedback->setUserFeedback($_SESSION['student_id'], $this->semester, $this->assignmentNumber, "Failed to compiled", $markId);

            header("location: /assignment?num=$this->assignmentNumber");
            die();
        }
        else
        {
            $result = $this->runAssignmentTests();

            if (!$result) {
                $markId = $mark->setUsersMark($this->studentId, $this->assignmentNumber, $this->semester, 0);
                $feedback->setUserFeedback($_SESSION['student_id'], $this->semester, $this->assignmentNumber, "Infinite Loop", $markId);
                header("location: /assignment?num=$this->assignmentNumber");
                die();
            }
        }

        $assignmentsToCheck = $this->compareOutputs();

        /**
         * This checks if the assignment output matches the master output
         * - If the assignment's output is identical to the master output
         *          - Award 10 marks and update feedback
         * - Else
         *  - There are differences in one or more assignment output vs master output
         *          - Begin checking assignments
         */
//        $assignmentsToCheck = $this->compareOutputs();

//        if (!is_array($assignmentsToCheck)) {
//            $markId = $mark->setUsersMark($this->studentId, $this->assignmentNumber, $this->semester, 10);
//            $feedback->setUserFeedback($_SESSION['student_id'], $this->semester, $this->assignmentNumber, "All test cases passed", $markId);
//            header("location: /assignment?num=$this->assignmentNumber");
//            die();
//        }
//        else
//        {
//            $feedbackStr = "";
//            $markAss = 10;
//            //print_r($assignmentsToCheck);

            for ($j = 0; $j < count($assignmentsToCheck); $j++) {
                echo "Assignment being checked : $assignmentsToCheck[$j] <br>";

                // Loads the output of the two assignments in question
                $masterOutput = $this->getMasterOutput($assignmentsToCheck[$j]);
                $studentOutput = $this->getAssignmentOutput($assignmentsToCheck[$j]);

                // Gets the lowest line count from the two files
                $masterLineCount = count($masterOutput);
                $studentLineCount = count($studentOutput);
                $lowLineCount = ($masterLineCount < $studentLineCount ? $studentLineCount : $masterLineCount);

                // Find the line that the two strings differ on
                // Sets the two variables to be lowercase strings
                $masterOutputDifferLine = "";
                $studentOutputDifferLine = "";
                for ($i = 0; $i < $lowLineCount; $i++) {
                    if (!strcasecmp($masterOutput[$i], $studentOutput[$i]) == 0) {
                        $masterOutputDifferLine = strtolower($masterOutput[$i]);
                        $studentOutputDifferLine = strtolower($studentOutput[$i]);
                        break;
                    }
                }

                // Removes all white space
                $masterOutputDifferLine = preg_replace('/\s+/', '', $masterOutputDifferLine);
                $studentOutputDifferLine = preg_replace('/\s+/', '', $studentOutputDifferLine);

                // Checks which line has the least amount of characters
                $masterCharCount = strlen($masterOutputDifferLine);
                $studentCharCount = strlen($studentOutputDifferLine);
                $lowCharCount = ($masterCharCount > $studentCharCount ? $studentCharCount : $masterCharCount);

                // Finds which character the line differs on
                $differOnChar = -1;
                for ($i = 0; $i < $lowCharCount; $i++) {
                    if (strcasecmp($masterOutputDifferLine[$i], $studentOutputDifferLine[$i]) != 0) {
                        $differOnChar = $i;
                        break;
                    }
                }

                // There is a difference in the line at some point
                if ($differOnChar != -1) {
                    echo "Difference Chars : " . $masterOutputDifferLine[$differOnChar] . " " . $studentOutputDifferLine[$differOnChar] . "<br>";
                    echo $masterOutputDifferLine . "<br>" . $studentOutputDifferLine . "<br>";
                }

                echo "<br><br><br>";


            }

            die('died');


//            $markId = $mark->setUsersMark($this->studentId, $this->assignmentNumber, $this->semester, $markAss);
//            $feedback->setUserFeedback($_SESSION['student_id'], $this->semester, $this->assignmentNumber, "Failed on : " . $feedbackStr, $markId);
//            header("location: /assignment?num=$this->assignmentNumber");
//            die();
//        }

        header("location: /assignment?num=$this->assignmentNumber");
    }


    /**
     * Returns the assignment number the user is testing
     */
    private function getAssignmentNumber($httpRef)
    {
        $number = explode('=', $httpRef);
        return $number[1];
    }

    /**
     * Uploads the file to the correct folder
     *  - Renames it to match the Assignment number
     */
    public function uploadFile()
    {
        $this->assignmentNumber = $this->getAssignmentNumber($_SERVER['HTTP_REFERER']);

        $target_dir = "/home/student/" . $_SESSION['student_id'] . "/A$this->assignmentNumber";

        // Removes all files in the folder currently
        system("sudo rm $target_dir/*");

        // Sets the target file
        $target_file = $target_dir . "/A$this->assignmentNumber.cpp";

        move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
        chmod($target_file, 0777);

    }


    /**
     * Method to get the students current assignment's mark
     */
    private function getCurrentMark()
    {
        $assignmentNumber = $this->getAssignmentNumber($_SERVER["REQUEST_URI"]);

        $mark = new Marks();
        $mark = $mark->getUsersMark($_SESSION['student_id'], $assignmentNumber, $this->semester);

        return $mark;
    }


    /**
     * Method to get the students assignment's feedback
     */
    private function getAssignmentFeedback()
    {
        $assignmentNumber = $this->getAssignmentNumber($_SERVER["REQUEST_URI"]);

        $feedback = new Feedback();
        $feedback = $feedback->getAssignmentFeedback($_SESSION['student_id'], $assignmentNumber, $this->semester);

        return $feedback;
    }


    /**
     * Compiles the assignment from a cpp file to a executable
     * - If it compiled into an executable, returns TRUE
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
            throw $e;
        }
    }

    public function getAssignmentOutput($assignmentNumber)
    {
        $studentId = $_SESSION['id'];
        chdir("/home/student/$this->studentId/A$this->assignmentNumber");
        $file = file("result$assignmentNumber" . ".txt");

        return $file;
    }

    public function getMasterOutput($assignmentNumber)
    {
        $studentId = $_SESSION['id'];
        chdir("/home/student/$this->studentId/A$this->assignmentNumber");
        $file = file("gabarito$assignmentNumber" . ".txt");

        return $file;
    }


    /**
     * Uses the created executable to test a variety of input
     *  - Output from these tests is directed into a results text file
     */
    private function runAssignmentTests()
    {
        $assignmentController = new MarkingConfig();

        /* Copies all the test files into the students Assignment Folder */
        $assignmentController->copyTestFiles($this->assignmentNumber);

        /* Gets the assignment Commands from the config file */
        $cmd = $assignmentController->getAssignmentCommands($this->assignmentNumber);

        /* Gets the number of tests each assignment will perform */
        $testNumber = $assignmentController->getAssignmentTestNumber();

        /* Navigates to the assignment folder */
        chdir("/home/student/$this->studentId/A$this->assignmentNumber");

        for ($i = 1; $i <= $testNumber; $i++) {
            system(trim($cmd[$i]), $result);

            // If there is an infinite loop
            if ($result == 139) {
                return false;
            }
        }
        return true;
    }


    /**
     * Checks to see if the assignment output and master output are identical
     * - If they are not, the numbers of the test which failed is returned
     */
    private function compareOutputs()
    {
        $assignmentController = new MarkingConfig();

        /* Gets the assignment Commands from the config file */
        $cmd = $assignmentController->getCompareCommands($this->assignmentNumber);

        /* Gets the number of tests each assignment will perform */
        $testNumber = $assignmentController->getAssignmentTestNumber();

        /* Navigates to the assignment folder */
        chdir("/home/student/$this->studentId/A$this->assignmentNumber");

        for ($i = 1; $i <= $testNumber; $i++) {
            system(trim($cmd[$i]), $result);
        }

        $toCheck = "";
        $j = 0;
        for ($i = 1; $i <= $testNumber; $i++) {
            if (filesize("compare$i.txt") != 0) {
                $toCheck[$j] = $i;
                $j++;
            }
        }

        return $toCheck;
    }

}

