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
    private $assignmentMark;

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



        /**
         * This checks if the assignment output matches the master output
         * - If the assignment's output is identical to the master output
         *          - Award 10 marks and update feedback
         * - Else
         *  - There are differences in one or more assignment output vs master output
         *     - Count the number of tests failed in the array and apply to VAR
         *          - Begin checking assignments
         *          - Award marks if the output is correct
         */

        $assignmentsToCheck = $this->compareOutputs();

        if (!in_array('FAILED', $assignmentsToCheck)) {
            $markId = $mark->setUsersMark($this->studentId, $this->assignmentNumber, $this->semester, 10);
            $feedback->setUserFeedback($_SESSION['student_id'], $this->semester, $this->assignmentNumber, "All test cases passed", $markId);
            header("location: /assignment?num=$this->assignmentNumber");
            die();
        }
        else
        {
            /*Structure
             *  Test X
             *    - Pass/Passed with errors/Failed
             *
             *  Possible Causes
             *
             *  Desired output
             */


            $databaseFeedback = array();
            $this->assignmentMark = 0;
            for ($j = 1; $j <= 10; $j++) {
                array_push($databaseFeedback, "<pre><h3><u>Test $j</u></h3>");

                if ($assignmentsToCheck[$j] === 'PASSED') {
                    array_push($databaseFeedback, "<h4 style=\"color:red\">Passed</h4></pre>");
                    $this->assignmentMark++;
                    continue;
                }

                // Loads the output of the two assignments in question
                $masterOutput = $this->getMasterOutput($j);
                $studentOutput = $this->getAssignmentOutput($j);




                // Converts each line to lower case
                $masterOutputFiltered='';
                $studentOutputFiltered='';
                for ($i = 0; $i < count($masterOutput); $i++) {
                    $masterOutputFiltered[$i] = trim(strtolower($masterOutput[$i]));
                }
                for ($i = 0; $i < count($studentOutput); $i++) {
                    $studentOutputFiltered[$i] = trim(strtolower($studentOutput[$i]));
                }




                /* NB. No animals were hurt in the making of the below function..
                 *
                 * Loops through each line and checks to see if the lines are identical
                 * If the lines are not identical
                 *   - splits the lines ignoring white space
                 *   - does a string compare on each word
                 *      - if the word is a number
                 *        * check to see if the diff is less than 5 *
                 *           - if less than 5
                 *              * set as passed *
                 *           - else it is more than 5
                 *              * set as failed *
                 *
                 * Push the line number difference onto the array
                 */

                $closeEnough = array();
                $differOnLine = array();
                $lowLineCount = (count($masterOutput) < count($studentOutput) ? count($masterOutput) : count($studentOutput));

                for ($i = 0; $i < $lowLineCount; $i++) {

                    // If the filtered lines are not identical, proceed
                    if (strcmp($masterOutputFiltered[$i], $studentOutputFiltered[$i]) != 0) {
                        $masterSplit = preg_split('/\s+/', $masterOutputFiltered[$i]);
                        $studentSplit = preg_split('/\s+/', $studentOutputFiltered[$i]);
                        $elemCount = (count($masterSplit) < count($studentSplit) ? count($masterSplit) : count($studentSplit));

                        for ($k = 0; $k < $elemCount; $k++) {
                            if (strcmp($masterSplit[$k], $studentSplit[$k]) != 0) {
                                if (is_numeric($masterSplit[$k]) && is_numeric($studentSplit[$k])) {
                                    $masterVal = $masterSplit[$k];
                                    $studentVal = $studentSplit[$k];
                                    $difference = abs($masterVal - $studentVal);
                                    if ($difference < 5) {
                                        array_push($closeEnough, "PASSED");
                                    } else {
                                        array_push($closeEnough, "FAILED");
                                    }
                                }
                            }
                        }
                        array_push($differOnLine, $i);
                    }
                }

                /**
                 ******* Setting of Test Status ********
                 *
                 * Count up the number of PASSED vs FAILED
                 *
                 * If there are more passes than fails
                 *  * Set test as passed *
                 *  * Increment mark by 1 *
                 * Else
                 *  * Set test as failed
                 */

                $totalCount = array_count_values($closeEnough);
                $targetOutputFailed = false;
                if (isset($totalCount['PASSED'])) {
                    if (isset($totalCount['FAILED'])) {
                        if ($totalCount['PASSED'] > $totalCount['FAILED']) {
                            array_push($databaseFeedback, "<h4 style=\"color:red\">Passed With Errors</h4>");
                            $this->assignmentMark++;
                        } else {
                            array_push($databaseFeedback, "<h4 style=\"color:red\">Failed</h4>");
                            $targetOutputFailed = true;
                        }
                    } else {
                        array_push($databaseFeedback, "<h4 style=\"color:red\">Passed With Errors</h4>");
                        $this->assignmentMark++;
                    }
                } else {
                    array_push($databaseFeedback, "<h4 style=\"color:red\">Failed</h4>");
                    $targetOutputFailed = true;
                }




                /*****************************************************************
                 * Here I want to perform assignment specific fault analysis to report to a user
                 * EG for
                 *    - assignment 1 - 'Cannot open file %s. Exiting' and
                 *    - assignment 2 - 'too many operators'
                 *    - assignment 7 - 'Heap is already empty'
                 *
                 * Basically check which assignment it is, see if any of the errors exist
                 *****************************************************************/



                // Stores the line differences in an array
                //    - What is stored is the original lines that failed
                //    - Limit of 10 lines
                $linesForReview = array();
                $count = 0;
                foreach ($differOnLine as $line) {
                    $numLine = $line+1;

                    if (strlen($masterOutput[$line]) > 100) {
                        $masterOutput[$line] = substr($masterOutput[$line],0, 100) . "..." . PHP_EOL;
                    }
                    if (strlen($studentOutput[$line]) > 100) {
                        $studentOutput[$line] = substr($studentOutput[$line],0, 100) . "..." . PHP_EOL;
                    }

                    $string = "Line $numLine<br>$masterOutput[$line]" . "<b style=\"color:red\">$studentOutput[$line]</b><br>";
                    array_push($linesForReview, $string);

                    $count++;
                    if ($count == 10) {
                        array_push($linesForReview, "...First 10 faults displayed.");
                        break;
                    }
                }
                $linesForReview = implode($linesForReview);
                array_push($databaseFeedback, $linesForReview);

                // If there is only 2 lines of output
                // Skip printing the desired output
                if (count($studentOutput) <= 2) {
                    array_push($databaseFeedback, "</pre>");
                    continue;
                }








                // Start listing possible causes
                array_push($databaseFeedback, "<h4><u>Possible Causes</u></h4>");
                array_push($databaseFeedback, "<ul>");

                /**
                 * First step
                 * Remove white space to check if the strings match
                 *    - if the number of chars on each line do not match, report to user
                 * Count number of spaces on each line
                 *    - if the number of spaces on each line do not match, report to user
                 */
                $hasAddedChars = false;
                $hasAddedWhiteSpace = false;
                foreach ($differOnLine as $line) {
                    $masterOutputRmvWSLine = preg_replace('/\s+/', '', $masterOutput[$line]);
                    $studentOutputRmvWSLine = preg_replace('/\s+/', '', $studentOutput[$line]);

                    if (!$hasAddedChars) {
                        $masterCharCount = strlen($masterOutputRmvWSLine);
                        $studentCharCount = strlen($studentOutputRmvWSLine);
                        if ($masterCharCount != $studentCharCount) {
                            array_push($databaseFeedback, "<li>Additional characters in output</li>");
                            $hasAddedChars = true;
                        }
                    }


                    if (!$hasAddedWhiteSpace) {
                        $wsCountStudent = substr_count($studentOutput[$line], " ");
                        $wsCountMaster = substr_count($masterOutput[$line], " ");
                        if ($wsCountStudent != $wsCountMaster) {
                            array_push($databaseFeedback, "<li>Additional whitespace (spaces) in output</li>");
                            $hasAddedWhiteSpace = true;
                        }
                    }

                    if ($targetOutputFailed) {
                        array_push($databaseFeedback, "<li>Target output failed, check algorithms</li>");
                    }

                }
                array_push($databaseFeedback, "</ul><br>");












                // Provide the students with a sample of the desired output
                $sampleMasterOutput = array();
                $count = 0;
                array_push($sampleMasterOutput, "<h4><u>Desired Output</u></h4>");
                foreach ($masterOutput as $output) {
                    $string = $output;
                    if (strlen($string) > 100) {
                        $string = substr($string, 0, 50);
                        $string = $string . " ..." . PHP_EOL;
                    }
                    array_push($sampleMasterOutput, $string);
                    $count++;
                    if ($count == 20) {
                        array_push($sampleMasterOutput, "...etc");
                        break;
                    }
                }

                $sampleMasterOutput = implode($sampleMasterOutput);
                array_push($databaseFeedback, $sampleMasterOutput);


                array_push($databaseFeedback, "</pre>");

            }

            $databaseFeedback = implode($databaseFeedback);
            $markId = $mark->setUsersMark($this->studentId, $this->assignmentNumber, $this->semester, $this->assignmentMark);
            $feedback->setUserFeedback($_SESSION['student_id'], $this->semester, $this->assignmentNumber, $databaseFeedback, $markId);

            header("location: /assignment?num=$this->assignmentNumber");
            die();
        }

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

        $toCheck = array();
        for ($i = 1; $i <= $testNumber; $i++) {
            if (filesize("compare$i.txt") == 0) {
                $toCheck[$i] = 'PASSED';
            } else {
                $toCheck{$i} = 'FAILED';
            }
        }

        return $toCheck;
    }

}
