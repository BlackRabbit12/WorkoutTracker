<?php

/**
 * Class WorkoutController.
 *
 * @author Bridget Black
 * @author Chad Drennan
 * 2020-03-11
 * Last Updated: 2020-03-13
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
        //reset session
        $_SESSION = array();

        //set to false if there are errors
        $isValid = true;

        //if the input 'first name' is set, then we can look at everything else
        if (isset($_POST['first-name'])) {
            //get the first name
            $firstName = $_POST['first-name'];
            //make first name input sticky
            $this->_f3->set('stickyFirstName', $firstName);
            //validate first name
            if ($this->_val->validString($firstName)) {
                //start creating a user object
                //$_SESSION['userObj']->setFirstName($firstName);
            }
            else {
                $isValid = false;
                $this->_f3->set("errors['first-name']", "Please enter a valid name");
            }

            //get last name, repeat steps from above ^^
            $lastName = $_POST['last-name'];
            $this->_f3->set("stickyLastName", $lastName);
            if ($this->_val->validString($lastName)) {
                //$_SESSION['userObj']->setLastName($lastName);
            }
            else {
                $isValid = false;
                $this->_f3->set("errors['last-name']", "Please enter a valid name");
            }

            //get username, repeat steps from above ^^
            $userName = $_POST['user-name'];
            $this->_f3->set("stickyUserName", $userName);
            if ($this->_val->validString($userName)) {
                //$_SESSION['userObj']->setUserName();
            }
            else {
                $isValid = false;
                $this->_f3->set("errors['user-name']", "Please enter a valid username");
            }

            //get password, repeat steps from above ^^
            $password = $_POST['password'];
            $this->_f3->set("stickyPassword", $password);
            if ($this->_val->validString($password)) {
                //$_SESSION['userObj']->setPassword();
            }
            else {
                $isValid = false;
                $this->_f3->set("errors['password']", "Please enter a valid password");
            }

            //confirm password
            $passwordConfirmation = $_POST['password-confirmation'];
            $this->_f3->set("stickyPasswordConfirmation", $passwordConfirmation);
            if (!($this->_val->passwordMatch($password, $passwordConfirmation))) {
                $isValid = false;
                $this->_f3->set("errors['password-confirmation']", "Passwords do not match");
            }

            //if all of the inputs had valid data, reroute new user to home page
            if ($isValid) {
                //get if premium
                if (isset($_POST['is-pro'])) {
                    if ($_POST['is-pro'] == 'pro') {
                        $premium = $_POST['is-pro'];
                        //create a premium-user
                        $_SESSION['userPremiumObj'] = new PremiumUser($firstName, $lastName, $userName, $password, 1);
                        $GLOBALS['db']->insertUser($_SESSION['userPremiumObj'], 1);
                    }
                }
                else {
                    //create a user
                    $_SESSION['userObj'] = new User($firstName, $lastName, $userName, $password);
                    $GLOBALS['db']->insertUser($_SESSION['userObj'], 0);
                }

                //route to home page
                $this->_f3->reroute('/');
            }
        }

        //go back to registration if user input was not validated and rerouted
        echo \Template::instance()->render('views/registration.html');
    }
}