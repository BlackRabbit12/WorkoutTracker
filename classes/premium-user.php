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

    /**
     * Get the user's username.
     * @return mixed
     */
    function getUserName()
    {
        return $this->_userName;
    }

    /**
     * Reset the user's username.
     * @param $newUserName
     */
    function setResetUserName($newUserName)
    {
        $this->_userName = $newUserName;
    }
}
