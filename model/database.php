<?php

require_once('/home/cdrennan/config-workout.php');

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
                FROM workout
                ORDER BY workout_name';
        
        $statement = $this->_dbh->prepare($sql);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    function getWorkoutMuscleGroups($workoutId) {
        $sql = 'SELECT muscle_group_name
                FROM workout
                    INNER JOIN workout_muscle_group ON workout.workout_id = workout_muscle_group.workout_id
                    INNER JOIN muscle_group ON workout_muscle_group.muscle_group_id = muscle_group.muscle_group_id
                WHERE workout.workout_id = :workoutId';

        $statement = $this->_dbh->prepare($sql);
        $statement->bindParam(':workout_id', $workoutId);

        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
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