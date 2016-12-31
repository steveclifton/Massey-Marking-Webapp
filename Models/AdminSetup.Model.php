<?php

namespace Marking\Models;

use Marking\Exceptions\CustomException;
use Marking\Controllers\MarkingSetup;
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

        if (isset($data[0]['semester'])) {
            return $data[0]['semester'];
        }
        header('location: /welcome');
        die();
    }


    public function getNumberOfAssignments()
    {
        $sql = "SELECT num_assignments 
                FROM `admin_setup` 
                ORDER BY created_at DESC 
                LIMIT 1
                ";

        $stm = $this->database->prepare(($sql), array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

        $stm->execute();

        $data = $stm->fetchAll(PDO::FETCH_ASSOC);

        if (isset($data[0]['num_assignments'])) {
            return $data[0]['num_assignments'];
        }
        header('location: /welcome');
        die();
    }

    public function getNumberOfTests()
    {
        $sql = "SELECT num_tests 
                FROM `admin_setup` 
                ORDER BY created_at DESC 
                LIMIT 1
                ";

        $stm = $this->database->prepare(($sql), array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

        $stm->execute();

        $data = $stm->fetchAll(PDO::FETCH_ASSOC);
        if (isset($data[0]['num_tests'])) {
            return $data[0]['num_tests'];
        }
        header('location: /welcome');
        die();
    }


}
