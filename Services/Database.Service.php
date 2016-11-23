<?php
/**
 * This class is not used by default
 * Only here if it is required in the future
 */



namespace Marking\Services;

use mysqli;
use mysqli_sql_exception;
use mysqli_result;


/**
 * Class Database
 *
 * Used to create a database connection using MySQLi
 */
class Database extends mysqli
{
    protected static $instance;


    /**
     * Constructs a mysqli connection
     */
    public function __construct()
    {
        /**
         * Need to be setup if using a MySqli Connection
         */
//        mysqli_report(MYSQLI_REPORT_OFF);
//
//        @parent::__construct();
//
//        if (mysqli_connect_errno()) {
//            throw new \mysqli_sql_exception();
//        }
    }

    /**
     * Creates an instance of the mysqli connection
     *
     * @return Database
     */
    public static function getInstance()
    {
        if (! self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }


    /**
     * Used to query the database
     *
     * @param string $query
     * @return \mysqli_result
     */
    public function query($query)
    {
        if (!$this->real_query($query)) {
            throw new mysqli_sql_exception($this->error);
        }
        $result = new mysqli_result($this);
        return $result;
    }


}
