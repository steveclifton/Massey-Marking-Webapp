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
