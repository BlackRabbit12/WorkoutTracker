<?php

/**
 * User class.
 *
 * @author Bridget Black
 * @author Chad Drennan
 * 2020-03-11
 * Last Updated: 2020-03-11
 */

class User
{
    //all Users have:
    private $_firstName;
    private $_lastName;
    private $_email;
    private $_password;


    function __construct($firstName, $lastName, $email, $password)
    {
        $this->_firstName = $firstName;
        $this->_lastName = $lastName;
        $this->_email = $email;
        $this->_password = $password;
    }

    function getFullName()
    {
        return $this->_firstName." ".$this->_lastName;
    }

    function getEmail()
    {
        return $this->_email;
    }

    function getPassword()
    {
        return $this->_password;
    }

    function setResetPassword($newPassword)
    {
        $this->_password = $newPassword;
    }
}
