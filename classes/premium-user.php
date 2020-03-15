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
    private $_id;

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
        parent::__construct($firstName, $lastName, $userName, $password, $id);
        $this->_premium = $premium;
    }

    /**
     * Gets if the user is a "premium" or "normal" user.
     * @return string
     */
    function typeOfMember()
    {
        return "premium";
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
