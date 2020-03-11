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
        //collect the 'workout_name' column from the 'workout' table
        $workouts = $GLOBALS['db']->getAllWorkouts();
        $this->_f3->set('workouts', $workouts);


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
