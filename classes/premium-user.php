<?php

/**
 * Premium User class.
 *
 * @author Bridget Black
 * @author Chad Drennan
 * 2020-03-11
 * Last Updated: 2020-03-13
 */
class PremiumUser extends User
{
    private $_premium;

    /**
     * PremiumUser constructor.
     * @param $firstName
     * @param $lastName
     * @param $userName
     * @param $password
     * @param $premium
     * @param $id
     */
    function __construct($firstName, $lastName, $userName, $password, $premium, $id = -1)
    {
        parent::__construct($firstName, $lastName, $userName, $password);
        $this->_premium = $premium;
    }

    /**
     * Get user's premium status.
     * @return mixed
     */
    function getPremium()
    {
        return $this->_premium;
    }
}
