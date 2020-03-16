<?php

/**
 * Class WorkoutController.
 *
 * @author Bridget Black
 * @author Chad Drennan
 * 2020-03-11
 * Last Updated: 2020-03-15
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
     * Workout home page, displays 'workout cards' for today and the past 6 days. Allows users to update and edit
     * their cards with choices of muscle groups and targeted workouts.
     * Home page only accessible if logged in as a valid user.
     */
    public function homeRoute()
    {
        //test for if user is already logged in
        if (isset($_SESSION['userObj'])) {
            //collect all workout names and muscle group names
            $workouts = $GLOBALS['db']->getAllWorkouts();
            $allMuscleGroups = $GLOBALS['db']->getAllMuscleGroups();

            $userId = -1;
            if (isset($_SESSION['userObj'])) {
                $userId = $_SESSION['userObj']->getId();
            }

            $dayPlans = $GLOBALS['db']->getUserDayPlans($userId);

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
            $this->_f3->set('dayPlans', $dayPlans);

            //render home page
            $view = new Template();
            echo $view->render('views/home.html');
        }
        //if they are not logged in, reroute to login
        else {
            $this->_f3->reroute('/login');
        }
    }

    /**
     * Login page, only allows valid users to login to the home page of Workout Tracker. If a user is trying to
     * access the home page without a session saved, user will be redirected here.
     */
    public function loginRoute()
    {
        //reset session
        $_SESSION = array();

        //set to false if there are errors
        $isValid = true;

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            //get variables
            $userName = $_POST['user-name'];
            $this->_f3->set("stickyUserName", $userName);
            $userPassword = $_POST['password'];
            $this->_f3->set("stickyPassword", $userPassword);

            //validation for if one or both of the fields are empty
            if ($this->_val->validString($userName) == false && $this->_val->validString($userPassword) == false) {
                $this->_f3->set("errors['password-login']", "Username and password empty");
            }
            else if ($this->_val->validString($userName) == false) {
                $this->_f3->set("errors['password-login']", "Username empty");
            }
            else if ($this->_val->validString($userPassword) == false) {
                $this->_f3->set("errors['password-login']", "password empty");
            }
            //database queried and validation continues after both fields have input
            else {
                //get an array that is filled with the correct username and password, OR get an
                // empty array if they don't match any in the database
                $userCred = $GLOBALS['db']->getLoginCredentials($userName, $userPassword);

                //the username entered doesn't match any in the database
                if (empty($userCred)) {
                    $isValid = false;
                    $this->_f3->set("errors['password-login']", "Username or password did not match a user");
                }
                //the username entered matched one in the database
                else {
                    //if the passwords do not match, then we do not have a user
                    if (!password_verify($userPassword, $userCred['password'])) {
                        $isValid = false;
                        $this->_f3->set("errors['password-login']", "Password incorrect");
                    }
                }

                //if correct username and password entered
                if ($isValid) {
                    //create a user object
                    if ($userCred['isPro'] == 1) {
                        $_SESSION['userObj'] = new Premium_User($userCred['first_name'], $userCred['last_name'],
                            $userCred['handle'], $userCred['password'], $userCred['isPro'], $userCred['user_id']);
                    }
                    else {
                        $_SESSION['userObj'] = new User($userCred['first_name'], $userCred['last_name'],
                            $userCred['handle'], $userCred['password'], $userCred['user_id']);
                    }
                    //route to home page
                    $this->_f3->reroute('/');
                }
             }
        }

        //else the request was 'GET', go to login until credentials entered and verified
        echo \Template::instance()->render('views/login.html');
    }

    /**
     * Registration page inputs a new user to the database after their inputs have been validated. Once registered
     * a user will be auto-directed to the home page.
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
            if (!$this->_val->validString($firstName)) {
                $isValid = false;
                $this->_f3->set("errors['first-name']", "Please enter a valid name");
            }

            //get last name, repeat steps from above ^^
            $lastName = $_POST['last-name'];
            $this->_f3->set("stickyLastName", $lastName);
            if (!$this->_val->validString($lastName)) {
                $isValid = false;
                $this->_f3->set("errors['last-name']", "Please enter a valid name");
            }

            //get username, repeat steps from above ^^
            $userName = $_POST['user-name'];
            $this->_f3->set("stickyUserName", $userName);
            if ($this->_val->validString($userName)) {
                //get username where it matches the user's input username
                $userHandles = $GLOBALS['db']->uniqueUserName($userName);

                //if the array was not empty, then the username is "in use"
                if (!empty($userHandles)) {
                        $isValid = false;
                        $this->_f3->set("errors['user-name']", "Username is already in use, please choose new username.");
                }
                //if the array was empty, then the username is unique
            }
            else {
                $isValid = false;
                $this->_f3->set("errors['user-name']", "Please enter a valid username");
            }

            //get password, repeat steps from above ^^
            $password = $_POST['password'];
            $this->_f3->set("stickyPassword", $password);
            if ($this->_val->validString($password)) {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
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
                        $_SESSION['userObj'] = new Premium_User($firstName, $lastName, $userName, $hashedPassword, 1);
                        //get user's database id from query
                        $userID = $GLOBALS['db']->insertUser($_SESSION['userObj'], 1);
                        $_SESSION['userObj']->setID($userID);
                    }
                }
                //not premium
                else {
                    //create a user
                    $user = new User($firstName, $lastName, $userName, $hashedPassword);

                    // Save user and retrieve Id
                    $userId = $GLOBALS['db']->insertUser($user, 0);
                    $user->setId($userId);

                    $_SESSION['userObj'] = $user;
                }

                //route to home page
                $this->_f3->reroute('/');
            }
        }

        //go back to registration if user input was not validated and rerouted
        echo \Template::instance()->render('views/registration.html');
    }

    /**
     * Logs each workout a user does. This function retrieves the workout name, weight, and repetitions the user
     * has selected and inserts it into the user's currently selected 'day plan' in the database, if no day plan
     * exists in the database yet then one is created. Steps are completed through calls to: getWorkoutIdByName,
     * getDayPlanId and insertDayPlan.
     * @throws Exception
     */
    public function logWorkout()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $userId = -1;
            if (isset($_SESSION['userObj'])) {
                $userId = $_SESSION['userObj']->getId();
            }

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

    public function editWorkout()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $workoutLogId = $_POST['workoutLogId'];
            $weight = $_POST['weight'];
            $reps = $_POST['reps'];

            $GLOBALS['db']->updateWorkoutLog($workoutLogId, $weight, $reps);
        }
    }

    public function deleteWorkout()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $workoutLogId = $_POST['workoutLogId'];
            $GLOBALS['db']->deleteWorkoutLog($workoutLogId);
        }
    }
}