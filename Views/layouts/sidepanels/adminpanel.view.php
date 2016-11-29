<?php

/**
 * If the user is logged in (SessionID is set) then a list of available
 *   assignments will be displayed.
 *
 * jQuery used on the Class = assignment, and uses the ID to determine which
 *   was clicked
 */


if (isset($_SESSION['id'])) {
    echo "
            <h4 style='padding-left: 12px; font-size: 18pt;'>Admin Panel</h4>
            <ul class=\"nav\">
          ";

    echo "<li><a href=\"/adduser\" style='text-decoration: underline; font-size: 12pt;'>Single Add User</a></li>";

}
echo "</ul>";




