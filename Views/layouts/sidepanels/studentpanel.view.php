<?php

use Marking\Controllers\MarkingSetup;
/**
 * If the user is logged in (SessionID is set) then a list of available
 *   assignments will be displayed.
 *
 * jQuery used on the Class = assignment, and uses the ID to determine which
 *   was clicked
 */


if (isset($_SESSION['id'])) {
    echo "
            <h4 style='padding-left: 12px; font-size: 20pt;'>Assignments</h4>
            <ul class=\"nav\">
          ";
    $assignments = new MarkingSetup();
    $assignments = $assignments->getAssignmentNumber();


    for ($i = 1; $i <= $assignments; $i++) {
        echo "<li><a class=\"assignment\" href=\"/assignment?num=$i\" id=\"$i\" style='text-decoration: underline; font-size: 12pt;'>Assignment $i</a></li>";
    }
}
echo "</ul>";




