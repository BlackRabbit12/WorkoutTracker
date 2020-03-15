<?php

require_once('/home/cdrennan/config-workout.php');
//require_once('/home/bblackgr/config-workout.php');

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
    function getAllWorkouts()
    {
        //query database
        $sql = 'SELECT *
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
    function getWorkoutMuscleGroups($workoutId)
    {
        //query database
        $sql = 'SELECT muscle_group_name
                FROM workout
                    INNER JOIN workout_muscle_group ON workout.workout_id = workout_muscle_group.workout_id
                    INNER JOIN muscle_group ON workout_muscle_group.muscle_group_id = muscle_group.muscle_group_id
                WHERE workout.workout_id = :workoutId';
        //prepare statement
        $statement = $this->_dbh->prepare($sql);
        //bind parameters
        $statement->bindParam(':workoutId', $workoutId);
        //execute the statement
        $statement->execute();
        //return the result
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    function getAllMuscleGroups()
    {
        $sql = 'SELECT muscle_group_name
                FROM muscle_group';

        $statement = $this->_dbh->prepare($sql);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    function getWorkoutIdByName($workoutName)
    {
        $sql = 'SELECT workout_id
                FROM workout
                WHERE workout_name = :workoutName';

        $statement = $this->_dbh->prepare($sql);

        $statement->bindParam(':workoutName', $workoutName);

        $statement->execute();
        return $statement->fetch();
    }

    function getDayPlanId($userId, $date) {
        $sql = 'SELECT day_plan_id
                FROM day_plan
                WHERE `date` = :date AND user_id = :userId';

        $statement = $this->_dbh->prepare($sql);

        $statement->bindParam(':userId', $userId);
        $statement->bindParam(':date', $date);

        $statement->execute();
        return $statement->fetch();
    }

    function insertDayPlan($userId, $date)
    {
        $sql = 'INSERT INTO day_plan (user_id, `date`)
                VALUES (:userId, :date)';

        $statement = $this->_dbh->prepare($sql);

        $statement->bindParam(':userId', $userId);
        $statement->bindParam(':date', $date);

        $statement->execute();
    }

    function insertWorkoutLog($userId, $workoutName, $date, $weight, $reps)
    {
        // Get workout Id
        $workoutIdResult = $this->getWorkoutIdByName($workoutName);
        echo '|' . $workoutName . '|';
        if (empty($workoutIdResult)) {
            echo "test 2";
            return false;
        }
        $workoutId = $workoutIdResult['workout_id'];

        // Get day plan Id
        $dayPlanId = $this->getDayPlanId($userId, $date);

        // Insert new day plan if one does not exist
        if (empty($dayPlanId)) {
            $this->insertDayPlan($userId, $date);
            $dayPlanId = $this->_dbh->lastInsertId();
        }
        else {
            $dayPlanId = $dayPlanId['day_plan_id'];
        }
        echo "test 3";
        $sql = 'INSERT INTO workout_log (day_plan_id, workout_id, weight, repetitions)
                VALUES (:dayPlanId, :workoutId, :weight, :reps)';

        $statement = $this->_dbh->prepare($sql);

        $statement->bindParam(':dayPlanId', $dayPlanId);
        $statement->bindParam(':workoutId', $workoutId);
        $statement->bindParam(':weight', $weight);
        $statement->bindParam(':reps', $reps);

        $statement->execute();

        echo "test 4";
    }
}
