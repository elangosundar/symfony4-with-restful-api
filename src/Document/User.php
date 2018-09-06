<?php
/*
 * Create User class document for MongoDB
 * Author : Elangovan.Sundar
 */

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * @MongoDB\Document
 */
class User
{
    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @MongoDB\Field(type="string")
     */

    protected $userFirstName;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $userLastName;

    /**
     * @MongoDB\Field(type="hash")
     */
    protected $userEmail;

    /**
     * @MongoDB\Field(type="hash")
     */
    protected $userMobileNumber;

    /**
     * @MongoDB\Field(type="date")
     */
    protected $userDateofBirth;

    /**
     * @MongoDB\Field(type="hash")
     */
    protected $userEducationOthers;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $userBloodGroup;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $userGender;
    
    /**
     * @MongoDB\Field(type="string")
     */
    protected $userSchoolStudies;
    
    /**
     * @MongoDB\Field(type="string")
     */
    protected $userUGStudies;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $userPGStudies;

    /**
     * @MongoDB\Field(type="date")
     */
    protected $create_date;

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }
    
    public function setUserFirstName($userFirstName)
    {
        $this->userFirstName = $userFirstName;
    }

    public function getUserFirstName()
    {
        return $this->userFirstName;
    }

    public function setUserLastName($userLastName)
    {
        $this->userLastName = $userLastName;
    }

    public function getUserLastName()
    {
        return $this->userLastName;
    }
    
    public function setUserEmail($userEmail)
    {
        $this->userEmail = $userEmail;
    }

    public function getUserEmail()
    {
        return $this->userEmail;
    }

    public function setUserMobileNumber($userMobileNumber)
    {
        $this->userMobileNumber = $userMobileNumber;
    }

    public function getUserMobileNumber()
    {
        return $this->userMobileNumber;
    }

    public function setUserDateofBirth($userDateofBirth)
    {
        $this->userDateofBirth = $userDateofBirth->format('Y-m-d H:i:s');
    }

    public function getUserDateofBirth()
    {
        return $this->userDateofBirth;
    }

    public function setUserEducationOthers($userEducationOthers)
    {
        $this->userEducationOthers = $userEducationOthers;
    }

    public function getUserEducationOthers()
    {
        return $this->userEducationOthers;
    }

    public function setUserBloodGroup($userBloodGroup)
    {
        $this->userBloodGroup = $userBloodGroup;
    }

    public function getUserBloodGroup()
    {
        return $this->userBloodGroup;
    }

    public function setUserGender($userGender)
    {
        $this->userGender = $userGender;
    }

    public function getUserGender()
    {
        return $this->userGender;
    }

    public function setUserSchoolStudies($userSchoolStudies)
    {
        $this->userSchoolStudies = $userSchoolStudies;
    }

    public function getUserSchoolStudies()
    {
        return $this->userSchoolStudies;
    }

    public function setUserUGStudies($userUGStudies)
    {
        $this->userUGStudies = $userUGStudies;
    }

    public function getUserUGStudies()
    {
        return $this->userUGStudies;
    }

    public function setUserPGStudies($userPGStudies)
    {
        $this->userPGStudies = $userPGStudies;
    }

    public function getUserPGStudies()
    {
        return $this->userPGStudies;
    }
}
