<?php

/**
 * UserValidator validates all information of a user when they register and as they use their account.
 *
 * @author Bridget Black
 * @author Chad Drennan
 * @version 1.0
 * 2020-02-17
 * Last Updated: 2020-03-15
 */

class UserValidator
{
    /**
     * Test if the string is valid, not empty or only spaces.
     * @param $str
     * @return bool
     */
    function validString($str)
    {
        return !empty(trim($str));
    }

    /**
     * Tests if the password and password-confirmation match, validation already happened to the password.
     * @param $password
     * @param $confirmation
     * @return bool
     */
    function passwordMatch($password, $confirmation)
    {
        return strcmp($password, $confirmation) == 0;
    }
}
