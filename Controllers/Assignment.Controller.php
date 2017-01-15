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
        $mSemester = new MarkingSetup();
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
            $feedback->setUserFeedback($_SESSION['student_id'], $this->semester, $this->assignmentNumber, "<pre>Failed to compile</pre>", $markId);

            header("location: /assignment?num=$this->assignmentNumber");
            die();
        }
        else
        {
            $result = $this->runAssignmentTests();

            if (!$result) {
                $markId = $mark->setUsersMark($this->studentId, $this->assignmentNumber, $this->semester, 0);
                $feedback->setUserFeedback($_SESSION['student_id'], $this->semester, $this->assignmentNumber, "<pre>System Timeout - Possibly an Infinite Loop</pre>", $markId);
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
            $feedback->setUserFeedback($_SESSION['student_id'], $this->semester, $this->assignmentNumber, "<pre>All Test Cases Passed, Well Done</pre>", $markId);
            header("location: /assignment?num=$this->assignmentNumber");
            die();
        }



        /**
         * Here one or more of the tests have failed
         * Begins parsing the output from each test and checking it
         */
        else
        {
            $databaseFeedback = array();
            $this->assignmentMark = 0;


            for ($j = 1; $j <= 10; $j++) {
                array_push($databaseFeedback, "<pre><h3><u>Test $j</u></h3>");

                // If the assignment was compared previously and classed as passed
                // Don't check, assign mark and continue
                if ($assignmentsToCheck[$j] === 'PASSED') {
                    array_push($databaseFeedback, "<h4 style=\"color:red\">Passed</h4></pre>");
                    $this->assignmentMark++;
                    continue;
                }

                // Loads the output of the two assignments in question
                $masterOutput = $this->getMasterOutput($j);
                $studentOutput = $this->getAssignmentOutput($j);


                $lineStatus = array();
                $differOnLine = array();

                $hasAddedCaseOrWs = false;

                $masterOutputFiltered= '';
                $studentOutputFiltered='';

                // Checks to see which of the files has the lowest line
                // This can be helpful to provide some feedback to the student if the output is somewhat correct
                $lowLineCount = (count($masterOutput) < count($studentOutput) ? count($masterOutput) : count($studentOutput));

                // Begin looping through the files lines
                for ($i = 0; $i < $lowLineCount; $i++) {

                    // If the lines match without being modified
                    // - Passed
                    if (strcmp($masterOutput[$i], $studentOutput[$i]) == 0) {
                        array_push($lineStatus, "PASSED");
                        continue;
                    }

                    // Removes whitespace from front and end and converts to lower case
                    $masterOutputFiltered[$i] = trim(strtolower($masterOutput[$i]));
                    $studentOutputFiltered[$i] = trim(strtolower($studentOutput[$i]));

                    // If the lines match after they have been trimmed / case corrected
                    // - Passed
                    if (strcmp($masterOutputFiltered[$i], $studentOutputFiltered[$i]) == 0) {
                        array_push($lineStatus, "PASSED WITH ERRORS");
                        array_push($differOnLine, $i);
                        if (!$hasAddedCaseOrWs) {
                            $hasAddedCaseOrWs = true;
                        }
                    }

                    // The lines do not match after being trimmed/converted to lower case
                    // Remove all white space and check to see if they match then
                    else if (strcmp($masterOutputFiltered[$i], $studentOutputFiltered[$i]) != 0) {
                        $masterOutputRmvWSLine = preg_replace('/\s+/', '', $masterOutputFiltered[$i]);
                        $studentOutputRmvWSLine = preg_replace('/\s+/', '', $studentOutputFiltered[$i]);

                        // If they match after removing all white space, passed
                        if (strcmp($masterOutputRmvWSLine, $studentOutputRmvWSLine) == 0) {
                            array_push($lineStatus, "PASSED WITH ERRORS");
                            array_push($differOnLine, $i);
                        }

                        /**
                         * Below executes if after trimming, removing whitespace and changing case it still does not match
                         */
                        else {

                            // Check if the assignment is 3 or 7
                            // If it is, Explode the line and see if the numbers match
                            if ($this->assignmentNumber == 3 || $this->assignmentNumber == 7) {

                                // Replaces spaces with commas in case multiple spaces exist
                                $masterOutputComa = preg_replace('/\s+/', ',', $masterOutputFiltered[$i]);
                                $studentOutputComa = preg_replace('/\s+/', ',', $studentOutputFiltered[$i]);

                                // Explodes the line by comma
                                $explodeMasterLine = explode(",", $masterOutputComa);
                                $explodeStudentLine = explode(",", $studentOutputComa);

                                // If one line has more values than the other, it fails
                                if (count($explodeMasterLine) != count($explodeStudentLine)) {
                                    array_push($differOnLine, $i);
                                    array_push($lineStatus, "FAILED");
                                }

                                // Loop through the lines and find which token is a number
                                else {

                                    for ($inc = 0; $inc < count($explodeMasterLine); $inc++) {

                                        // Checks to see if one token or the other is a number
                                        if (is_numeric($explodeMasterLine[$inc]) || is_numeric($explodeStudentLine[$inc])) {

                                            // Checks to make sure both are numbers, if they are tests are done
                                            // If they are not, the output fails
                                            if (is_numeric($explodeMasterLine[$inc]) && is_numeric($explodeStudentLine[$inc])) {

                                                $setup = new MarkingSetup();

                                                // Sets the high and low values that a students result can be in
                                                $masterResultHigh = (float)$explodeMasterLine[$inc] * (float)$setup->getHighTolerance();
                                                $masterResultLow = (float)$explodeMasterLine[$inc] * (float)$setup->getLowTolerance();


                                                // If the students result is between the high and low tolerance, pass
                                                if ($explodeStudentLine[$inc] < $masterResultHigh && $explodeStudentLine[$inc] > $masterResultLow) {
                                                    array_push($lineStatus, "PASSED INSIDE TOLERANCE");
                                                    array_push($differOnLine, $i);
                                                } else {
                                                    array_push($differOnLine, $i);
                                                    array_push($lineStatus, "FAILED");
                                                }
                                            }

                                            // If one is a number and one is not a number, output is faulty
                                            else {
                                                array_push($differOnLine, $i);
                                                array_push($lineStatus, "FAILED");
                                                if (!$hasAddedCaseOrWs) {
                                                    $hasAddedCaseOrWs = true;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }


                /**
                 ******* Setting of Test Status *******
                 * Count up the number of PASSED vs FAILED
                 * If there is any FAILED
                 *  - Set test as Failed
                 * Else If there are only Passed in the array
                 *  - Set as Passed
                 * Else if there are a mix of Passed and Passed with Errors
                 *  - Set as Passed with Errors
                 */

                $totalCount = array_count_values($lineStatus);
                //if ($j == 8) { var_dump($totalCount);die(); }

                if (isset($totalCount['FAILED'])) {
                    array_push($databaseFeedback, "<h4 style=\"color:red\">Failed</h4>");
                }

                else if (isset($totalCount['PASSED']) && !isset($totalCount['PASSED WITH ERRORS']) && !isset($totalCount['PASSED INSIDE TOLERANCE'])) {
                    $this->assignmentMark++;
                    array_push($databaseFeedback, "<h4 style=\"color:red\">Passed</h4>");
                }

                else if (isset($totalCount['PASSED']) || isset($totalCount['PASSED WITH ERRORS']) || isset($totalCount['PASSED INSIDE TOLERANCE'])) {
                    $this->assignmentMark++;
                    array_push($databaseFeedback, "<h4 style=\"color:red\">Passed with Errors</h4>");
                }


                // Enters the line differences into the feedback
                //    - What is stored is the original lines that failed and the line it was compared to
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

                    $string = "Line $numLine<br><b style=\"color:green\">$masterOutput[$line]</b>" . "<b style=\"color:red\">$studentOutput[$line]</b><br>";
                    array_push($linesForReview, $string);

                    $count++;
                    if ($count == 10) {
                        array_push($linesForReview, "...First 10 faults displayed.");
                        break;
                    }
                }

                // Push the lines that differ into the feedback array
                $linesForReview = implode($linesForReview);
                array_push($databaseFeedback, $linesForReview);




                // Start listing possible causes
                /**
                 * First step
                 * Remove white space to check if the strings match
                 *    - if the number of chars on each line do not match, report to user
                 * Count number of spaces on each line
                 *    - if the number of spaces on each line do not match, report to user
                 */
                array_push($databaseFeedback, "<h4><u>Possible Causes</u></h4>");
                array_push($databaseFeedback, "<ul>");

                if ($hasAddedCaseOrWs) {
                    array_push($databaseFeedback, "<li>Possible added whitespace (spaces)</li>");
                    array_push($databaseFeedback, "<li>Possible character case difference</li>");
                } else {
                    array_push($databaseFeedback, "<li>Possible differences in target output</li>");
                }

                array_push($databaseFeedback, "</ul>");



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

                // Push the final outputs into the database feedback array
                $sampleMasterOutput = implode($sampleMasterOutput);
                array_push($databaseFeedback, $sampleMasterOutput);
                array_push($databaseFeedback, "</pre>");
            }

            // Sets the users mark and updates the users feedback
            $databaseFeedback = implode($databaseFeedback);
            $markId = $mark->setUsersMark($this->studentId, $this->assignmentNumber, $this->semester, $this->assignmentMark);
            $feedback->setUserFeedback($_SESSION['student_id'], $this->semester, $this->assignmentNumber, $databaseFeedback, $markId);

            header("location: /assignment?num=$this->assignmentNumber");
            die();
        }
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
        $assignmentController = new MarkingSetup();

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

            // A correct run of the test returns a 0
            // If it is not a 0, possible seg fault / infinite loop
            if ($result != 0) {
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
        $assignmentController = new MarkingSetup();

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
