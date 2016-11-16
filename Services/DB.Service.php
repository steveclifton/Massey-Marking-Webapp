<?php
/*
 * All Connections to MySQL server are performed through this class
 *
 */

namespace Marking\Services;

use PDO;
use PDOException;
use Marking\Services\Evn;


/**
 * All DB connections done through this class
 *
 * Class DB
 * @package Marking\Services
 */
class DB extends PDO
{
    protected static $instance;

    /**
     * Constructs a new Database object
     *
     * Uses the Env class to get login details
     */
    public function __construct()
    {
        $db = new Evn();
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

