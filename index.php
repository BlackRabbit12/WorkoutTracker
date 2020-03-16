<?php

/**
 * Workout Tracker Web Site Project. Allow a user to register and login/out Users can use the site to keep track
 * of their previous six days of logged workouts, log the current day, and upgraded users can TODO: insert upgrade
 *
 * @author Bridget Black
 * @author Chad Drennan
 * @version 1.0
 * 2020-02-17
 * Last Updated: 2020-03-15
 */

//Error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

//Require file
require_once('vendor/autoload.php');

//Start a session
session_start();

//Create instance of FatFree Framework
$f3 = Base::instance();

//Set Debugger
$f3->set('DEBUG',3);

//Instantiate Database and Controller
$db = new Database();
$controller = new WorkoutController($f3);

//Default/Home route
$f3->route('GET /', function () {
    $GLOBALS['controller']->homeRoute();
});

//Login route
$f3->route('GET|POST /login', function () {
    $GLOBALS['controller']->loginRoute();
});

//Registration route
$f3->route('GET|POST /register', function () {
    $GLOBALS['controller']->registerRoute();
});

// Inserts a workout log
$f3->route('POST /log-workout', function () {
    $GLOBALS['controller']->logWorkout();
});

// Updates a workout log
$f3->route('POST /edit-workout', function () {
    $GLOBALS['controller']->editWorkout();
});

// Deletes a workout log
$f3->route('POST /delete-workout', function () {
    $GLOBALS['controller']->deleteWorkout();
});

//Run FatFree Framework
$f3->run();