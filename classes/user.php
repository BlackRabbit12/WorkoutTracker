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
    private $_userName;
    private $_password;


    /**
     * User constructor.
     * @param $firstName
     * @param $lastName
     * @param $userName
     * @param $password
     */
    function __construct($firstName = "first", $lastName = "last", $userName = "user", $password = "password")
    {
        $this->_firstName = $firstName;
        $this->_lastName = $lastName;
        $this->_userName = $userName;
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

   function getUserName()
   {
       return $this->_userName;
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
     * Set the user's first name.
     * @param $newFirstName
     */
    function setFirstName($newFirstName)
    {
        $this->_firstName = $newFirstName;
    }

    /**
     * Set the user's last name.
     * @param $newLastName
     */
    function setLastName($newLastName)
    {
        $this->_lastName = $newLastName;
    }

    /**
     * Set the user's password.
     * @param $newPassword
     */
    function setPassword($newPassword)
    {
        $this->_password = $newPassword;
    }
}
