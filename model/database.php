<?php

//require_once('/home/cdrennan/config-workout.php');
require_once('/home/bblackgr/config-workout.php');

/**
 * Database class interactions with database and workout tracker. Handles all database queries.
 *
 * @author Bridget Black
 * @author Chad Drennan
 * 2020-03-11
 * Last Updated: 2020-03-15
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
     * @param $workoutId id of workout
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

    /**
     * Gets all columns from database for requested user IF the username being asked for exists in the database,
     * if it does not exist, the returned array is null.
     * @param $desiredUserName string username
     * @return array query result
     */
    function uniqueUserName($desiredUserName)
    {
        //query database
        $sql = 'SELECT * FROM `user` WHERE handle = :username';

        //prepare statement
        $statement = $this->_dbh->prepare($sql);

        //bind parameter
        $statement->bindParam(':username', $desiredUserName);

        //execute the statement
        $statement->execute();

        //return the result
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Inserts a user into the database and returns their newly created user_id.
     * @param $user user object
     * @param $pro 1 if user is pro
     * @return id of user that was inserted
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

        return $this->_dbh->lastInsertId();
    }

    /**
     * Gets all of the muscle group names from the database.
     * @return array results of query
     */
    function getAllMuscleGroups()
    {
        $sql = 'SELECT muscle_group_name
                FROM muscle_group';

        $statement = $this->_dbh->prepare($sql);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Gets all of the columns from database for requested user IF the username being asked for exists in the
     * database, if not the returned array is null.
     * @param $userName string handle of user
     * @param $userPassword string password of user
     * @return mixed results
     */
    function getLoginCredentials($userName, $userPassword)
    {
        //define query
        $sql = "SELECT * FROM `user` WHERE handle = :username";

        //prepare the statement
        $statement = $this->_dbh->prepare($sql);

        //bind the parameters
        $statement->bindParam(':username', $userName);

        //execute statement
        $statement->execute();

        //return the query
        return $statement->fetch();
    }

    /**
     * Gets the workout_id of the workout requested.
     * @param $workoutName name of workout
     * @return mixed querie result
     */
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

    /**
     * Get the day_plan_id of the date and user_id being requested.
     * @param $userId number id of user
     * @param $date string date of dayplan
     * @return mixed querie result
     */
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

    /**
     * Inserts a day plan into the database for a specific date for a single user_id.
     * @param $userId number id of user
     * @param $date string date of day plan
     */
    function insertDayPlan($userId, $date)
    {
        $sql = 'INSERT INTO day_plan (user_id, `date`)
                VALUES (:userId, :date)';

        $statement = $this->_dbh->prepare($sql);

        $statement->bindParam(':userId', $userId);
        $statement->bindParam(':date', $date);

        $statement->execute();
    }

    /**
     * Inserts a workout log into the database after getting the workout_id, and day_plan_id.
     * @param $userId Id of user
     * @param $workoutName string name of workout
     * @param $date string date of workout log
     * @param $weight number weight of workout log
     * @param $reps number weight of workout log
     * @return bool
     */
    function insertWorkoutLog($userId, $workoutName, $date, $weight, $reps)
    {
        // Get workout Id
        $workoutIdResult = $this->getWorkoutIdByName($workoutName);

        if (empty($workoutIdResult)) {
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

        $sql = 'INSERT INTO workout_log (day_plan_id, workout_id, weight, repetitions)
                VALUES (:dayPlanId, :workoutId, :weight, :reps)';

        $statement = $this->_dbh->prepare($sql);

        $statement->bindParam(':dayPlanId', $dayPlanId);
        $statement->bindParam(':workoutId', $workoutId);
        $statement->bindParam(':weight', $weight);
        $statement->bindParam(':reps', $reps);

        $statement->execute();
    }

    /**
     * Get the user's workout logs and return them to the user's day plan.
     * @param $dayPlanId number id of day plan to get  workout logs for
     * @return array workout logs query results
     */
    function getWorkoutLogsForDayPlan($dayPlanId)
    {
        $sql = 'SELECT workout_log_id, workout_name, workout_log.workout_id, weight, repetitions
                FROM workout_log
                    INNER JOIN workout ON workout_log.workout_id = workout.workout_id
                WHERE day_plan_id = :dayPlanId';

        $statement = $this->_dbh->prepare($sql);

        $statement->bindParam(':dayPlanId', $dayPlanId);

        $statement->execute();
        return $statement->fetchAll();
    }

    /**
     * Get the user's workout log and day plan.
     * @param $userId Id of user to get day plans of
     * @return array day plan query results
     * @throws Exception
     */
    function getUserDayPlans($userId)
    {
        $dayPlans = [];
        $dt = new DateTime();

        // Get this week's day plans
        for ($i = 0; $i < 7; $i++) {
            $date = $dt->format('Y-m-d');

            $sql = 'SELECT day_plan_id
                    FROM day_plan
                    WHERE user_id = :userId AND `date` = :date';

            $statement = $this->_dbh->prepare($sql);

            $statement->bindParam(':userId', $userId);
            $statement->bindParam(':date', $date);

            // Get previous day for next iteration
            $dt->modify('-1 day');

            $statement->execute();
            $dayPlanIdResult = $statement->fetch();

            if (isset($dayPlanIdResult)) {
                $dayPlanId = $dayPlanIdResult['day_plan_id'];
                $dayPlans[$i] = $this->getWorkoutLogsForDayPlan($dayPlanId);
            }
        }
        return $dayPlans;
    }

    /**
     * Update the user's workout log after an edit.
     * @param $workoutLogId Id for workout_log table
     * @param $weight number new weight to update with
     * @param $reps number new repetitions to update with
     */
    function updateWorkoutLog($workoutLogId, $weight, $reps)
    {
        $sql = 'UPDATE workout_log
                SET weight = :weight,
                    repetitions = :reps
                WHERE workout_log_id = :workoutLogId';

        $statement = $this->_dbh->prepare($sql);

        $statement->bindParam(':workoutLogId', $workoutLogId);
        $statement->bindParam(':weight', $weight);
        $statement->bindParam(':reps', $reps);

        $statement->execute();
    }

    /**
     * Delete a workout log from a user's day plan.
     * @param $workoutLogId The id for the workout_log table
     */
    function deleteWorkoutLog($workoutLogId)
    {
        $sql = 'DELETE FROM workout_log
                WHERE workout_log_id = :workoutLogId';

        $statement = $this->_dbh->prepare($sql);
        $statement->bindParam(':workoutLogId', $workoutLogId);
        $statement->execute();
    }

    /**
     * Retrieves all workouts that the user has not done in the past week
     * @param $userId Id of the user fetching workouts for
     * @return array query results of workouts
     * @throws exception
     */
    function getWorkoutsNotSelected($userId)
    {
        $dt = new DateTime();
        $dt->modify('-6 day');
        $date = $dt->format('Y-m-d');

        $sql = 'SELECT workout_name
                FROM workout
                WHERE workout_id NOT IN (
                    SELECT DISTINCT workout_id 
                    FROM workout_log
                        INNER JOIN day_plan ON workout_log.day_plan_id = day_plan.day_plan_id
                    WHERE user_id = :userId AND `date` > :date
                )';

        $statement = $this->_dbh->prepare($sql);
        $statement->bindParam(':userId', $userId);
        $statement->bindParam(':date', $date);

        $statement->execute();
        return $statement->fetchAll();
    }
}
