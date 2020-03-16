<?php

require_once('/home/cdrennan/config-workout.php');
//require_once('/home/bblackgr/config-workout.php');

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

    /**
     * Gets all columns from database for requested user IF the username being asked for exists in the database,
     * if it does not exist, the returned array is null.
     * @param $desiredUserName
     * @return array
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
     * @param $user
     * @param $pro
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
     * @return array
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
     * @param $userName
     * @param $userPassword
     * @return mixed
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
     * @param $workoutName
     * @return mixed
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
     * @param $userId
     * @param $date
     * @return mixed
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
     * @param $userId
     * @param $date
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
     * @param $userId
     * @param $workoutName
     * @param $date
     * @param $weight
     * @param $reps
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

    function getWorkoutLogsForDayPlan($dayPlanId)
    {
        $sql = 'SELECT workout_name, workout_log.workout_id, weight, repetitions
                FROM workout_log
                    INNER JOIN workout ON workout_log.workout_id = workout.workout_id
                WHERE day_plan_id = :dayPlanId';

        $statement = $this->_dbh->prepare($sql);

        $statement->bindParam(':dayPlanId', $dayPlanId);

        $statement->execute();
        return $statement->fetchAll();
    }

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
}
