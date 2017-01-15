<?php
/*
 * All Connections to MySQL server are performed through this class
 */

namespace Marking\Services;

use PDO;
use PDOException;
use Marking\Services\Env;


/**
 * Class DB
 *
 * All DB connections done through this class
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
        try {
            parent::__construct(getenv('DB_NAME'), getenv('DB_USER'), getenv('DB_PASS'));
        } catch (PDOException $Exception) {
            header('location: /error');
        }
    }

    /**
     * Allows the Database to be queried
     */
    public function query($statement, $mode = PDO::ATTR_DEFAULT_FETCH_MODE, $arg3 = null)
    {
        parent::query($statement);
    }

    /**
     * Returns an instance of the Database Connection
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new DB();
        }
        return self::$instance;
    }

}

