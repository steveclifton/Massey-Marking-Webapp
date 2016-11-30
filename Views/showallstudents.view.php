<?php

echo "<ul>";
foreach ($viewData as $user) {
    echo "<li>" . $user['first_name'] . " " . $user['last_name'] . " " . $user['student_id'] . "</li>";
}
echo "</ul>";

