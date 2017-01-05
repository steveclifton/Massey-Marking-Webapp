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

    public function getAllSemesters()
    {
        $sql = "SELECT semester 
                FROM `admin_setup` 
                ORDER BY created_at DESC 
                ";

        $stm = $this->database->prepare(($sql), array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

        $stm->execute();

        $data = $stm->fetchAll(PDO::FETCH_ASSOC);
        $semesters = array();
        foreach ($data as $sem) {
            array_push($semesters, $sem['semester']);
        }

        if (isset($semesters)) {
            return $semesters;
        }
        header('location: /welcome');
        die();
    }



    public function setMarkingSetup($numAss, $numTests, $semester)
    {
        $sql = "INSERT INTO `admin_setup` (`id`, `num_assignments`, `num_tests`, `semester`, `created_at`) 
                VALUES (NULL, '$numAss', '$numTests', '$semester', CURRENT_TIMESTAMP)
                ";

        $stm = $this->database->prepare(($sql), array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

        $stm->execute(array('$numAss, $numTests, $semester'));

        $data = $stm->fetchAll(PDO::FETCH_ASSOC);

        return;
    }

}
