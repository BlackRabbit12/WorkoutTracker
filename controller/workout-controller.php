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

        // Set hive variables
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
}
