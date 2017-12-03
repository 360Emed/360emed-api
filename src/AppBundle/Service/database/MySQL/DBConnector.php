<?php
/**
 * Created by PhpStorm.
 * User: humingtang
 * Date: 12/2/17
 * Time: 10:14 PM
 */

namespace AppBundle\Service\database\MySQL;


class DBConnector
{
    var $pdo;

    public function __construct()
    {
        $this->init();
    }
    /**
     * INitialize the DB Connectors
     */
    function init()
    {
        try {
            $this->pdo = new \PDO("mysql:host=" . Config::host . ";dbname=" . Config::dbname, Config::username, Config::password);
            // set the PDO error mode to exception
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        }
        catch(\PDOException $e)
        {
            echo $e->getMessage();
        }

    }
}