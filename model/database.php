<?php

/**
 * Database class interactions with database and workout tracker. TODO: improve description.
 *
 * @author Bridget Black
 * @author Chad Drennan
 * 2020-03-11
 * Last Updated: 2020-03-11
 */

//require_once('/home/cdrennan/config-workout.php');
require_once('/home/bblackgr/config-workout.php');

class Database
{
    private $_dbh;

    /**
     * Database constructor.
     */
    function __construct()
    {
        try {
            $this->_dbh = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        }
        catch (PDOException $ex) {
            echo $ex->getMessage();
        }
    }

    /**
     * Collect all values in the 'workout_name' column of the 'workout' table.
     * Values are displayed inside a modal on a user's home page.
     * @return array
     */
    function getAllWorkouts() {
        //define the query
        $sql = 'SELECT workout_name FROM workout';
        //prepare the statement
        $statement = $this->_dbh->prepare($sql);
        //no params to bind

        //execute the statement
        $statement->execute();
        //return the result
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}