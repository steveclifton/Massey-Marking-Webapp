<?php
    if (isset($_SESSION['user_type'])) {
        echo "<div class=\"col-sm-4 col-md-2\" id=\"sidebar\" role=\"navigation\" style=\"padding-top: 20px\">";
        if ($_SESSION['user_type'] == 'admin') {
            include('/var/www/marking/Views/layouts/sidepanels/adminpanel.view.php');
        } else {
            include('/var/www/marking/Views/layouts/sidepanels/studentpanel.view.php');
        }
        echo "</div>";
    }

