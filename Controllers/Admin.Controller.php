<?php

namespace Marking\Controllers;

/**
 * Class Admin
 *
 * Controller for Admin related management
 */
class Admin extends Base
{

    public function adminView()
    {
        $this->isAdminLoggedIn();

        $this->render('Admin', 'admin.view');
    }

    public function testingTools()
    {
        $this->isAdminLoggedIn();
        $assignment = new Assignment();

        $this->render('Admin', 'testtools.view');
    }


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


}