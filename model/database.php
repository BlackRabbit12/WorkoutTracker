<?php

//require_once('/home/cdrennan/config-workout.php');
require_once('/home/bblackgr/config-workout.php');

class Database
{
    private $_dbh;

    function __construct()
    {
        try {
            $this->_dbh = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        }
        catch (PDOException $ex) {
            echo $ex->getMessage();
        }
    }


    function getAllWorkouts() {
        $sql = 'SELECT workout_name
                FROM workout';
        
        $statement = $this->_dbh->prepare($sql);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}