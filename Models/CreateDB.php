<?php

namespace Marking\Models;

class CreateDB extends Base
{

    public function createTables()
    {
        $this->createNewTableAdminSetup();
        $this->createNewTableFeedback();
        $this->createNewTableMarks();
        $this->createNewTableUsers();
    }

    // Creates new 'Admin Setup' table in DB
    private function createNewTableAdminSetup()
    {
        $sql = "CREATE TABLE `admin_setup` (`id` int(11) NOT NULL, 
                                            `num_assignments` int(11) NOT NULL,
                                            `num_tests` int(11) NOT NULL,
                                            `semester` varchar(16) NOT NULL,
                                            `high_tolerance` varchar(12) NOT NULL,
                                            `low_tolerance` varchar(12) NOT NULL,
                                            `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
                                            ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
                ";

        $stm = $this->database->prepare(($sql), array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

        $result = $stm->execute();

        echo "Admin Setup :"  . var_dump($result). PHP_EOL;

        return;
    }

    // Creates new 'Feedback' table in DB
    private function createNewTableFeedback()
    {
        $sql = "CREATE TABLE `feedback` (`id` int(11) NOT NULL,
                                         `student_id` varchar(255) NOT NULL,
                                         `assignment_number` int(11) NOT NULL,
                                         `semester` varchar(255) NOT NULL,
                                         `feedback` longtext NOT NULL,
                                         `mark_id` int(10) UNSIGNED NOT NULL,
                                         `created_date` timestamp NULL DEFAULT NULL
                                          ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
                ";

        $stm = $this->database->prepare(($sql), array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

        $result = $stm->execute();

        echo "Feedback :"  . var_dump($result). PHP_EOL;

        return;
    }

    // Create new 'Marks' table in the DB
    private function createNewTableMarks()
    {
        $sql = "CREATE TABLE `marks` (`id` int(11) NOT NULL,
                                      `student_id` varchar(255) NOT NULL,
                                      `mark` int(10) UNSIGNED NOT NULL,
                                      `assignment_number` int(10) UNSIGNED NOT NULL,
                                      `semester` varchar(255) NOT NULL,
                                      `created_date` timestamp NULL DEFAULT NULL
                                      ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
               ";

        $stm = $this->database->prepare(($sql), array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

        $result = $stm->execute();

        echo "Marks :"  . var_dump($result). PHP_EOL;

        return;
    }

    // Create new 'Users' Table in the DB
    private function createNewTableUsers()
    {
        $sql = "CREATE TABLE `users` (`id` int(10) UNSIGNED NOT NULL,
                                      `first_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                                      `last_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                                      `user_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                                      `student_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                                      `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                                      `semester` varchar(12) COLLATE utf8_unicode_ci NOT NULL
                                      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
               ";

        $stm = $this->database->prepare(($sql), array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

        $result = $stm->execute();

        echo "Users :" . var_dump($result). PHP_EOL;

        return;
    }
}