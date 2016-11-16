<?php

namespace Marking\Services;

use mysqli;

class Database extends mysqli
{
    protected static $instance;


    /**
     * Constructs a mysqli connection
     *
     * ConnectToDatabase constructor.
     */
    public function __construct()
    {
        mysqli_report(MYSQLI_REPORT_OFF);

        @parent::__construct('localhost', 'root', 'root', 'marking');

        if (mysqli_connect_errno()) {
            throw new \mysqli_sql_exception();
        }
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
            throw new \mysqli_sql_exception($this->error);
        }
        $result = new \mysqli_result($this);
        return $result;
    }


}
