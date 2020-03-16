<?php

/**
 * User class.
 *
 * @author Bridget Black
 * @author Chad Drennan
 * 2020-03-11
 * Last Updated: 2020-03-15
 */
class User
{
    //all Users have:
    private $_firstName;
    private $_lastName;
    private $_userName;
    private $_password;
    private $_id;
    //private $_date;


    /**
     * User constructor.
     * @param $firstName
     * @param $lastName
     * @param $userName
     * @param $password
     * @param $id
     */
    function __construct($firstName, $lastName, $userName, $password, $id = -1)
    {
        $this->_firstName = $firstName;
        $this->_lastName = $lastName;
        $this->_userName = $userName;
        $this->_password = $password;
        //$this->_date = $this->getMembershipEndDate();
    }

    /**
     * Gets if the user is a "premium" or "normal" user.
     * @return string
     */
    function typeOfMember()
    {
        return "normal";
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
     * Get the user's first name.
     * @return mixed
     */
    function getFirstName()
    {
        return $this->_firstName;
    }

    /**
     * Get the user's last name.
     * @return mixed
     */
    function getLastName()
    {
        return $this->_lastName;
    }

    /**
     * Get the user's user name.
     * @return mixed
     */
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
     * Get the user's ID.
     * @return mixed
     */
    function getID()
    {
        return $this->_id;
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
     * Set the user's ID with a value from the database.
     * @param $id
     */
    function setID($id)
    {
        $this->_id = $id;
    }

    /**
     * Set the user's password.
     * @param $newPassword
     */
    function setPassword($newPassword)
    {
        $this->_password = $newPassword;
    }

    /**
     * Create the date the user joined.
     * @return string
     */
    function getMembershipEndDate()
    {
        /*
         * Create a date user joined and a date their membership expires.
         * Code comes from 'Jason' on Stack Overflow, $startDate is modified from original poster's code to
         * accommodate need of non-hard coded start date.
         * href=https://stackoverflow.com/questions/2870295/increment-date-by-one-month
         */
        $startDate = date('Y-m-d');
        $nMonths = 3; // choose how many months you want to move ahead (Membership is $nMonths long)
        return $this->endCycle($startDate, $nMonths);
    }

    /**
     * Add months to the member's current sign up date, pairs with 'getMembershipEndDate()'
     * @param $months
     * @param DateTime $dateObject
     * @return DateInterval|false
     * @throws Exception
     */
    function add_months($months, DateTime $dateObject)
    {
        $next = new DateTime($dateObject->format('Y-m-d'));
        $next->modify('last day of +' . $months . ' month');

        if ($dateObject->format('d') > $next->format('d')) {
            return $dateObject->diff($next);
        } else {
            return new DateInterval('P' . $months . 'M');
        }
    }

    /**
     * Finds the end date of the member's current sign up date, pairs with 'getMembershipEndDate()'
     * @param $d1
     * @param $months
     * @return string
     * @throws Exception
     */
    function endCycle($d1, $months)
    {
        $date = new DateTime($d1);

        // call second function to add the months
        $newDate = $date->add($this->add_months($months, $date));

        // goes back 1 day from date, remove if you want same day of month
        $newDate->sub(new DateInterval('P1D'));

        //formats final date to Y-m-d form
        return $newDate->format('Y-m-d');
    }

}
