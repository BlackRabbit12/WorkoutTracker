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

//Define default route
$f3->route('GET /', function () {
    $view = new Template();
    echo $view->render('views/home.html');
});

//Run FatFree Framework
$f3->run();