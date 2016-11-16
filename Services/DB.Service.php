<?php

namespace Marking\Services;

use PDO;
use PDOException;
use Marking\Services\evn;

class DB extends PDO
{
    protected static $instance;

    public function __construct()
    {
        $db = new evn();
        try {
            parent::__construct($db->database, $db->username, $db->password);
        } catch (PDOException $Exception) {
            header('location: /error');
        }
    }


    public function query($statement, $mode = PDO::ATTR_DEFAULT_FETCH_MODE, $arg3 = null)
    {
        parent::query($statement);
    }


    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new DB();
        }
        return self::$instance;
    }

}

