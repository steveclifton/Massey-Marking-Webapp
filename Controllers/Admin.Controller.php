<?php

namespace Marking\Controllers;


class Admin extends Base
{

    public function adminView()
    {
        $this->isAdminLoggedIn();

        $this->render('Admin', 'admin.view');
    }

}