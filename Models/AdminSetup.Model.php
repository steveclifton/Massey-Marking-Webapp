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
    /**
     * Returns the current semester
     */
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


    /**
     * Returns the number of assignments
     */
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

    /**
     * Returns the number of tests
     */
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

    /**
     * Returns the high tolerance
     */
    public function getHighTolerance()
    {
        $sql = "SELECT high_tolerance 
                FROM `admin_setup` 
                ORDER BY created_at DESC 
                LIMIT 1
                ";

        $stm = $this->database->prepare(($sql), array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

        $stm->execute();

        $data = $stm->fetchAll(PDO::FETCH_ASSOC);
        //var_dump($data); die();
        if (isset($data[0]['high_tolerance'])) {
            return $data[0]['high_tolerance'];
        }
        header('location: /welcome');
        die();
    }

    /**
     * Returns the low tolerance
     */
    public function getLowTolerance()
    {
        $sql = "SELECT low_tolerance 
                FROM `admin_setup` 
                ORDER BY created_at DESC 
                LIMIT 1
                ";

        $stm = $this->database->prepare(($sql), array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

        $stm->execute();

        $data = $stm->fetchAll(PDO::FETCH_ASSOC);
        if (isset($data[0]['low_tolerance'])) {
            return $data[0]['low_tolerance'];
        }
        header('location: /welcome');
        die();
    }
     * Returns all semesters in the database
     */
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


    /**
     * Creates an entry in the database for the marking systems current semester and setup
     */
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

    /**
     * Removes a semester from the database if it exists
     */
    public function removeSemester($semester)
    {
        $sql = "DELETE FROM `admin_setup` WHERE `admin_setup`.`semester` = '$semester'";

        $stm = $this->database->prepare(($sql), array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

        $stm->execute(array('$semester'));

        $data = $stm->fetchAll(PDO::FETCH_ASSOC);

        return;
    }



}
