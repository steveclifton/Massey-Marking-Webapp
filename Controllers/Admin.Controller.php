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

}