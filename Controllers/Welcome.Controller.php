<?php

namespace Marking\Controllers;

use Marking\Models\Feedback;
use Marking\Models\Marks;

/**
 * Class Welcome - aka Student Controller
 * Controller for Student related management
 */
class Welcome extends Base
{

    /**
     * Gets the users details from SESSION and passes to the view
     */
    public function processWelcome()
    {
        $this->isNotLoggedIn();

        $viewData['first_name'] = $_SESSION['first_name'];
        $viewData['last_name'] = $_SESSION['last_name'];

        /**
         * If the user is an Admin they are routed to the Admin view
         */
        if ($_SESSION['user_type'] == 'admin') {

            $this->render('Student Results', 'showcurrentstudents.view', $viewData);
            die();
        }

        /**
         * Else they are a user/student so they get the welcome page
         *  - Gets the users current marks and displays them on the welcome page
         */
        else {
            $semester = new MarkingSetup();
            $feedback = new Feedback();

            $viewData['semester'] = $semester->getCurrentSemester();
            $viewData['marks'] = $feedback->getMarkAndFeedback($_SESSION['student_id'], $viewData['semester']);


            $this->render('Welcome', 'welcome.view', $viewData);
        }
    }

}

