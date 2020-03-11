<?php

/**
 *
 * @author Bridget Black
 * @author Chad Drennan
 * @version 1.0
 * 2020-02-17
 * Last Updated: 2020-02-17
 * File Name: index.php
 * File Associations:
 */

//Start a session
session_start();

//Error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

//Require file
require_once('vendor/autoload.php');
//require('model/validate.php');

//Create instance of FatFree Framework
$f3 = Base::instance();
$f3->set('DEBUG',3);

$db = new Database();

//Define default route
$f3->route('GET /', function ($f3) {
    $workouts = $GLOBALS['db']->getAllWorkouts();
    $workoutMuscleGroups = [];

    foreach ($workouts as $currWorkout) {
        $muscleGroupsResults = $GLOBALS['db']->getWorkoutMuscleGroups($currWorkout['workout_id']);

        if ($muscleGroupsResults) {
            $muscleGroups = '[';

            foreach ($muscleGroupsResults as $currMuscleGroupResult) {
                $muscleGroups .= "'{$currMuscleGroupResult['muscle_group_name']}', ";
            }
            $muscleGroups = rtrim($muscleGroups, ', ') . ']';
            $workoutMuscleGroups[$currWorkout['workout_id']] = $muscleGroups;
        }
    }

    $f3->set('workouts', $workouts);
    $f3->set('workoutMuscleGroups', $workoutMuscleGroups);

    $view = new Template();
    echo $view->render('views/home.html');
});

//Define Login route
$f3->route('GET /login', function () {
    $view = new Template();
    echo $view->render('views/login.html');
});

//Run FatFree Framework
$f3->run();