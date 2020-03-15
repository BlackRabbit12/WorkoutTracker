<?php

/**
 * Class WorkoutController.
 *
 * @author Bridget Black
 * @author Chad Drennan
 * 2020-03-11
 * Last Updated: 2020-03-11
 */

class WorkoutController
{
    private $_f3;
    private $_val;

    /**
     * WorkoutController constructor.
     * @param $f3
     */
    public function __construct($f3)
    {
        $this->_f3 = $f3;
        $this->_val = new UserValidator();
    }

    /**
     * TODO: improve description
     * Workout home page.
     */
    public function homeRoute()
    {
        //collect all workout names and muscle group names
        $workouts = $GLOBALS['db']->getAllWorkouts();
        $allMuscleGroups = $GLOBALS['db']->getAllMuscleGroups();

        //muscle group workout javascript array
        $workoutMuscleGroups = [];
        //for all workouts,
        foreach ($workouts as $currWorkout) {
            //gets the muscle groups from database
            $muscleGroupsResults = $GLOBALS['db']->getWorkoutMuscleGroups($currWorkout['workout_id']);

            //if there were query results
            if ($muscleGroupsResults) {
                $muscleGroups = '[';
                //concat them into a string
                foreach ($muscleGroupsResults as $currMuscleGroupResult) {
                    $muscleGroups .= "\"{$currMuscleGroupResult['muscle_group_name']}\", ";
                }
                //trim extra chars on end of string
                $muscleGroups = rtrim($muscleGroups, ', ') . ']';
                $workoutMuscleGroups[$currWorkout['workout_id']] = $muscleGroups;
            }
        }

        $daysOfWeek = ['Today', 'Yesterday', '2 Days Ago', '3 Days Ago',
                    '4 Days Ago','5 Days Ago', '6 Days Ago'];

        // Set hive variables
        $this->_f3->set('daysOfWeek', $daysOfWeek);
        $this->_f3->set('workouts', $workouts);
        $this->_f3->set('muscleGroups', $allMuscleGroups);
        $this->_f3->set('workoutMuscleGroups', $workoutMuscleGroups);


        $view = new Template();
        echo $view->render('views/home.html');
    }

    /**
     * TODO: improve description.
     * Login page.
     */
    public function loginRoute()
    {
        echo \Template::instance()->render('views/login.html');
    }

    /**
     * TODO: improve description
     * Registration page.
     */
    public function registerRoute()
    {
        echo \Template::instance()->render('views/registration.html');
    }

    public function logWorkout()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            //var_dump($_POST);

            //TODO get userId from session
            //$userId = $_SESSION['userId'];
            $userId = 1;
            $workout = trim($_POST['workout']);
            $weight = $_POST['weight'];
            $reps = $_POST['reps'];

            // Get date of day plan
            $dayAdjustment = $_POST['dayAdjustment'];
            $dt = new DateTime();
            $dt->modify('-' . $dayAdjustment . ' day');
            $date = $dt->format('Y-m-d');

            $GLOBALS['db']->insertWorkoutLog($userId, $workout, $date, $weight, $reps);
        }
    }
}
