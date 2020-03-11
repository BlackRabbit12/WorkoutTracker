<?php

/**
 * Premium User class.
 *
 * @author Bridget Black
 * @author Chad Drennan
 * 2020-03-11
 * Last Updated: 2020-03-11
 */

class PremiumUser extends User
{
    //in addition, premium users have:
    private $_userName;

    function getUserName()
    {
        return $this->_userName;
    }

    function setResetUserName($newUserName)
    {
        $this->_userName = $newUserName;
    }
}
