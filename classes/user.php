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


    /**
     * User constructor.
     * @param $firstName
     * @param $lastName
     * @param $email
     * @param $password
     */
    function __construct($firstName, $lastName, $email, $password)
    {
        $this->_firstName = $firstName;
        $this->_lastName = $lastName;
        $this->_email = $email;
        $this->_password = $password;
    }

    /**
     * Get the user's first and last name combined.
     * @return string
     */
    function getFullName()
    {
        return $this->_firstName." ".$this->_lastName;
    }

    /**
     * Get the user's email address.
     * @return mixed
     */
    function getEmail()
    {
        return $this->_email;
    }

    /**
     * Get the user's password.
     * @return mixed
     */
    function getPassword()
    {
        return $this->_password;
    }

    /**
     * Reset the user's password.
     * @param $newPassword
     */
    function setResetPassword($newPassword)
    {
        $this->_password = $newPassword;
    }
}
