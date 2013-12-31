<?php

namespace EB\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use EB\RideBundle\Entity\Car;

/**
 * User
 *
 * @ORM\Table(name="fos_user")
 * @ORM\Entity(repositoryClass="EB\UserBundle\Entity\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="facebook_id", type="string", length=255, nullable=true)
     */
    protected $facebookId;

    /**
     * @var string
     *
     * @ORM\Column(name="facebook_access_token", type="string", length=255, nullable=true)
     */
    protected $facebookAccessToken;

    /**
     * @var string
     *
     * @ORM\Column(name="google_id", type="string", length=255, nullable=true)
     */
    protected $googleId;

    /**
     * @var string
     *
     * @ORM\Column(name="google_access_token", type="string", length=255, nullable=true)
     */
    protected $googleAccessToken;

    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=255)
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=255)
     */
    private $lastname;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255)
     */
    private $phone;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birth_date", type="date")
     */
    private $birthDate;

    /**
     * @var string
     *
     * @ORM\Column(name="work", type="string", length=255, nullable=true)
     */
    private $work;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_smoker", type="boolean", nullable=true)
     */
    private $isSmoker;

    /**
     * @var string
     *
     * @ORM\Column(name="favorite_music", type="string", length=255, nullable=true)
     */
    private $favoriteMusic;

    /**
     * @var string
     *
     * @ORM\Column(name="hobbies", type="string", length=255, nullable=true)
     */
    private $hobbies;

    /**
     * @var string
     *
     * @ORM\Column(name="personalDescription", type="string", length=255, nullable=true)
     */
    private $personalDescription;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_driver", type="boolean", nullable=true)
     */
    private $isDriver;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="driving_licence_date", type="date", nullable=true)
     */
    private $drivingLicenceDate;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="EB\RideBundle\Entity\Car", mappedBy="user")
     */
    private $cars;


    public function __construct()
    {
        parent::__construct();
        $this->cars = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $firstname
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * @return string 
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param string $lastname
     * @return User
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * @return string 
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param string $facebookId
     * @return User
     */
    public function setFacebookId($facebookId)
    {
        $this->facebookId = $facebookId;

        return $this;
    }

    /**
     * @return string
     */
    public function getFacebookId()
    {
        return $this->facebookId;
    }

    /**
     * @param string $facebookAccessToken
     * @return User
     */
    public function setFacebookAccessToken($facebookAccessToken)
    {
        $this->facebookAccessToken = $facebookAccessToken;

        return $this;
    }

    /**
     * @return string
     */
    public function getFacebookAccessToken()
    {
        return $this->facebookAccessToken;
    }

    /**
     * @param string $googleId
     * @return User
     */
    public function setGoogleId($googleId)
    {
        $this->googleId = $googleId;

        return $this;
    }

    /**
     * @return string
     */
    public function getGoogleId()
    {
        return $this->googleId;
    }

    /**
     * @param string $googleAccessToken
     * @return User
     */
    public function setGoogleAccessToken($googleAccessToken)
    {
        $this->googleAccessToken = $googleAccessToken;

        return $this;
    }

    /**
     * @return string
     */
    public function getGoogleAccessToken()
    {
        return $this->googleAccessToken;
    }

    /**
     * @param string $city
     * @return $this
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $address
     * @return $this
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $phone
     * @return $this
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param \DateTime $birthDate
     * @return $this
     */
    public function setBirthDate($birthDate)
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * @param string $work
     * @return $this
     */
    public function setWork($work)
    {
        $this->work = $work;

        return $this;
    }

    /**
     * @return string
     */
    public function getWork()
    {
        return $this->work;
    }

    /**
     * @param boolean $isSmoker
     * @return $this
     */
    public function setIsSmoker($isSmoker)
    {
        $this->isSmoker = $isSmoker;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsSmoker()
    {
        return $this->isSmoker;
    }

    /**
     * @param string $favoriteMusic
     * @return $this
     */
    public function setFavoriteMusic($favoriteMusic)
    {
        $this->favoriteMusic = $favoriteMusic;

        return $this;
    }

    /**
     * @return string
     */
    public function getFavoriteMusic()
    {
        return $this->favoriteMusic;
    }

    /**
     * @param string $hobbies
     * @return $this
     */
    public function setHobbies($hobbies)
    {
        $this->hobbies = $hobbies;

        return $this;
    }

    /**
     * @return string
     */
    public function getHobbies()
    {
        return $this->hobbies;
    }

    /**
     * @param string $personalDescription
     * @return $this
     */
    public function setPersonalDescription($personalDescription)
    {
        $this->personalDescription = $personalDescription;

        return $this;
    }

    /**
     * @return string
     */
    public function getPersonalDescription()
    {
        return $this->personalDescription;
    }

    /**
     * @param boolean $isDriver
     * @return $this
     */
    public function setIsDriver($isDriver)
    {
        $this->isDriver = $isDriver;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsDriver()
    {
        return $this->isDriver;
    }

    /**
     * @param \DateTime $drivingLicenceDate
     * @return $this
     */
    public function setDrivingLicenceDate($drivingLicenceDate)
    {
        $this->drivingLicenceDate = $drivingLicenceDate;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDrivingLicenceDate()
    {
        return $this->drivingLicenceDate;
    }

    /**
     * @return ArrayCollection
     */
    public function getCars()
    {
        return $this->cars;
    }

    /**
     * @param Car $car
     * @return $this
     */
    public function addCar(Car $car)
    {
        $this->cars->add($car);

        return $this;
    }

    /**
     * @param Car $car
     * @return $this
     */
    public function removeCar(Car $car)
    {
        $this->cars->removeElement($car);

        return $this;
    }
}
