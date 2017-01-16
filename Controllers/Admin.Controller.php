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
     * Displays the list of students and the results for each assignment
     * Main landing page for the admin view
     */
    public function showCurrentStudents()
    {
        $this->isAdminLoggedIn();

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
     * Allows the admin to control the setup of the marking system
     */
    public function markingConfig()
    {
        $this->isAdminLoggedIn();

        $setup = new AdminSetup();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            /* Checks to see if the semester already exists in the DB,
            *   Removes if does to ensure the latest entry is the current semester
            */
            $semesters = $setup->getAllSemesters();
            if (in_array($_POST['semester'], $semesters)) {
                $setup->removeSemester($_POST['semester']);
            }

            $setup->setMarkingSetup($_POST['numAss'],$_POST['numTests'],$_POST['semester'], $_POST['hightolerance'], $_POST['lowtolerance']);
            $viewData['updated'] = true;
        }

        $viewData['semester'] = $setup->getCurrentSemester();
        $viewData['numAss'] = $setup->getNumberOfAssignments();
        $viewData['numTests'] = $setup->getNumberOfTests();
        $viewData['high_tolerance'] = $setup->getHighTolerance();
        $viewData['low_tolerance'] = $setup->getLowTolerance();

        $this->render('Admin', 'markingsetup.view', $viewData);
    }


    /**
     * Allows the admin to edit students profiles
     */
    public function editStudentsProfiles()
    {
        $this->isAdminLoggedIn();

        $setup = new AdminSetup();
        $user = new User();
        $viewData['students'] = $user->getCurrentSemestersUsers($setup->getCurrentSemester());

        $this->render('Admin', 'editstudentprofile.view', $viewData);
    }

    /**
     * Allows the admin to edit students profiles
     */
    public function editStudent()
    {
        $this->isAdminLoggedIn();
        $setup = new AdminSetup();
        $user = new User();

        // Makes sure the request is valid
        if ((!isset($_GET['id'])) && (!isset($_POST['db_id']))) {
            header('location: /welcome');
            //var_dump($_GET);
            die();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $oldStudentId = $user->getStudentID($_POST['db_id']);
            $studentId = $user->updateStudentDetails($_POST);

            // If the student ID has changed, need to create new folders for it
            if ($oldStudentId != $studentId) {
                $folder = new Folders();
                $folder->createFolders($studentId);
            }

            $result = $user->getUserByStudentId($studentId);
            $viewData = $result[0];
            $viewData['updated'] = true;
        } else {
            $result = $user->getUserByStudentId($_GET['id']);
            $viewData = $result[0];
        }

        $this->render('Admin', 'editstudent.view', $viewData);
    }


    /**
     * Attempts to register a new user
     */
    public function addSingleUser()
    {
        $this->isAdminLoggedIn();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = $_POST;
            $user = new User();
            $userFolders = new Folders();

            try {
                /* Sets the user type */
                if (isset($data['user_type'])) {
                    $data['user_type'] = "admin";
                } else {
                    $data['user_type'] = "student";
                }

                /* If the user already exists, remove them from the DB */
                $checkStudent = $user->getUserByStudentId($data['student_id']);
                if (isset($checkStudent[0])) {
                    $user->removeExistingUser($data['student_id']);
                }
                /* Create user and folders */
                $user->create($data);
                $userFolders->createFolders($data['student_id']);

            } catch (\Exception $e) {
                header('location: /');
            }
        }
        $this->render('Add New User', 'adduser.view');
    }

    /**
     * Method to import a CSV list of students and create an account for them
     * Example
     *      StudentID,Firstname,Lastname
     *      123456,John,Smith
     */
    public function importCSVStudents()
    {
        $target_dir = "/home/Admin";

        // Removes all files in the folder currently
        system("sudo rm $target_dir/*");

        // Sets the target file
        $target_file = $target_dir . "/import.csv";

        move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
        chmod($target_file, 0777);

        /* Open CSV and create users */
        $csv = array_map('str_getcsv', file('/home/Admin/import.csv'));

        foreach ($csv as $line) {
            if (!isset($line[0]) || !isset($line[1]) || !isset($line[2])) {
                $viewData['success'] = false;
                break;
            }
            // Convert first char to upper case concat add student id
            $pass = strtoupper($line[1][0]) . $line[0];

            $this->createUser($line[0], $line[1], $line[2], $pass);
        }
        if (!isset($viewData['success'])) {
            $viewData['success'] = true;
        }
        $this->render('Add New User', 'adduser.view', $viewData);
    }


    /**
     * Method used by CSV import to create user and folders
     */
    private function createUser($studentId, $first, $last, $pass)
    {
        $user = new User();
        $userFolders = new Folders();

        /* If the user already exists, remove them from the DB */
        $checkStudent = $user->getUserByStudentId($studentId);
        if (isset($checkStudent[0])) {
            $user->removeExistingUser($studentId);
        }
        $data['first_name'] = $first;
        $data['last_name'] = $last;
        $data['student_id'] = $studentId;
        $data['password'] = $pass;
        $data['user_type'] = "student";


        /* Create user and folders */
        $user->create($data);
        $userFolders->createFolders($data['student_id']);
    }

}
