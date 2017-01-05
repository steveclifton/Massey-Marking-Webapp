<?php

namespace Marking\Controllers;

use Marking\Models\AdminSetup;
use Marking\Models\Marks;
use Marking\Models\User;

/**
 * Class Admin
 *
 * Controller for Admin related management
 */
class Admin extends Base
{

    /**
     * Loads the Admin View
     */
    public function adminView()
    {
        $this->isAdminLoggedIn();

        $this->render('Admin', 'admin.view');
    }

    public function showCurrentStudents()
    {
        $markConfig = new MarkingSetup();
        $user = new User();
        $marks = new Marks();

        (isset($_POST['semester'])) ? $semester = $_POST['semester'] : $semester = $markConfig->getCurrentSemester();

        $viewData['students'] = $user->getCurrentSemestersUsers($semester);
        $viewData['number_of_assignments'] = $markConfig->getAssignmentNumber();

        $viewData['data'] = array();

        // Loops through the students array and adds their marks to their array
        foreach ($viewData['students'] as $student) {
            $studentMarks = $marks->getOnlyUsersMarks($student['student_id'], $student['semester']);

            // Initialise the student assignments (in case they have none)
            $student['assignments'] = array();

            foreach ($studentMarks as $sMarks) {
                $assignmentNumber = $sMarks['assignment_number'];
                $assignmentMark = $sMarks['mark'];
                $student['assignments'][$assignmentNumber] = $assignmentMark;
            }
            array_push($viewData['data'], $student);
        }
        unset($viewData['students']);


        $viewData['semesters'] = $markConfig->getListOfSemesters();
        $viewData['current_semester'] = $semester;

        $this->render('Current Students Results', 'studentresults.view', $viewData);
    }


    /**
     * Loads the Admin Testing Tools view
     */
    public function testingTools()
    {
        $this->isAdminLoggedIn();
        $assignment = new Assignment();

        $this->render('Admin', 'testtools.view');
    }


    /**
     * Allows the Admin to upload a file to a folder
     */
    public function uploadFile()
    {
        $assignmentNumber = $_POST['assignment_number'];

        $target_dir = "/home/student/" . $_SESSION['student_id'] . "/A$assignmentNumber";

        // Removes all files in the folder currently
        system("sudo rm $target_dir/*");

        // Sets the target file
        $target_file = $target_dir . "/A$assignmentNumber.cpp";

        move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
        chmod($target_file, 0777);

        if (file_exists($target_file)) {
            $viewData['uploaded'] = $target_file;
        } else {
            $viewData['uploaded'] = false;
        }

        $this->render('Admin', 'testtools.view', $viewData);

    }

    /**
     * Allows the Admin to compile a file in a folder
     */
    public function compileFile()
    {
        $assignmentNumber = $_POST['assignment_number'];

        $target_dir = "/home/student/" . $_SESSION['student_id'] . "/A$assignmentNumber";
        $target_file = $target_dir . "/A$assignmentNumber";


        system("sudo g++ $target_dir/A$assignmentNumber.cpp -o $target_dir/A$assignmentNumber");

        if (file_exists($target_file)) {
            $viewData['compiled'] = $target_file;
        } else {
            $viewData['compiled'] = false;
        }

        $this->render('Admin', 'testtools.view', $viewData);
    }

    public function markingConfig()
    {
        $setup = new AdminSetup();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $result = $setup->setMarkingSetup($_POST['numAss'],$_POST['numTests'],$_POST['semester']);
            $viewData['updated'] = true;
        }

        $viewData['semester'] = $setup->getCurrentSemester();
        $viewData['numAss'] = $setup->getNumberOfAssignments();
        $viewData['numTests'] = $setup->getNumberOfTests();

        $this->render('Admin', 'markingsetup.view', $viewData);
    }

}
