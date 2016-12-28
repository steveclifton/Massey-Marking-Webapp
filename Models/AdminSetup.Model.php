<?php

namespace Marking\Models;

use Marking\Exceptions\CustomException;
use Marking\Controllers\MarkingConfig;
use PDO;

/**
 * Class Admin Setup
 *
 */
class AdminSetup extends Base
{
    public function getCurrentSemester()
    {
        $sql = "SELECT semester 
                FROM `admin_setup` 
                ORDER BY created_at DESC 
                LIMIT 1
                ";

        $stm = $this->database->prepare(($sql), array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

        $stm->execute();

        $data = $stm->fetchAll(PDO::FETCH_ASSOC);

        if (isset($data[0])) {
            return $data[0];
        }
        header('location: /welcome');
        die();
    }

}
