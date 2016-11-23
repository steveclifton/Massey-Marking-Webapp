<?php

namespace Marking\Controllers;

/**
 * Class Welcome - aka Student Controller
 *
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
            $this->render('Admin', 'admin.view', $viewData);
            return;
            die();
        }

        $this->render('Welcome', 'welcome.view', $viewData);
    }

}

