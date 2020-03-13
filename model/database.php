<?php

//require_once('/home/cdrennan/config-workout.php');
require_once('/home/bblackgr/config-workout.php');

/**
 * Database class interactions with database and workout tracker. TODO: improve description.
 *
 * @author Bridget Black
 * @author Chad Drennan
 * 2020-03-11
 * Last Updated: 2020-03-11
 */
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
        //query database
        $sql = 'SELECT workout_name
                FROM workout
                ORDER BY workout_name';
        //prepare statement
        $statement = $this->_dbh->prepare($sql);
        //no params to bind

        //execute the statement
        $statement->execute();
        //return the result
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Gets the muscle groups for each workout.
     * @param $workoutId
     * @return array
     */
    function getWorkoutMuscleGroups($workoutId) {
        //query database
        $sql = 'SELECT muscle_group_name
                FROM workout
                    INNER JOIN workout_muscle_group ON workout.workout_id = workout_muscle_group.workout_id
                    INNER JOIN muscle_group ON workout_muscle_group.muscle_group_id = muscle_group.muscle_group_id
                WHERE workout.workout_id = :workout_id';
        //prepare statement
        $statement = $this->_dbh->prepare($sql);
        //bind parameters
        $statement->bindParam(':workout_id', $workoutId);
        //execute the statement
        $statement->execute();
        //return the result
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Insert a user into the database.
     * @param $user
     * @param $pro
     */
    function insertUser($user, $pro)
    {
        if ($pro == 1) {
            //define the query
            $sql = "INSERT INTO user VALUES (default, :firstName, :lastName, :userName, :password, 1, :membershipEndDate)";
        }
        else {
            //define the query
            $sql = "INSERT INTO user VALUES (default, :firstName, :lastName, :userName, :password, 0, :membershipEndDate)";
        }

        //prepare the statement
        $statement = $this->_dbh->prepare($sql);

        //bind the parameters
        $statement->bindParam(':firstName', $user->getFirstName());
        $statement->bindParam(':lastName', $user->getLastName());
        $statement->bindParam(':userName', $user->getUserName());
        $statement->bindParam(':password', $user->getPassword());
        $statement->bindParam(':membershipEndDate', $user->getMembershipEndDate());

        //execute the statement
        $statement->execute();
    }
}

/* INSERTS
 *  INSERT INTO `workout` (`workout_id`, `workout_name`) VALUES
    (1, 'Push Ups'),
    (2, 'Sit Ups'),
    (3, 'Bench Press'),
    (4, 'Cleans'),
    (5, 'Curls'),
    (6, 'Jogging'),
    (7, 'Running'),
    (8, 'Rowing'),
    (9, 'Military Press'),
    (10, 'Squats'),
    (11, 'Lunges');
 *
 */