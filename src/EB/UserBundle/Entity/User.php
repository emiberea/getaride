<?php

namespace EB\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use FOS\MessageBundle\Model\ParticipantInterface;
use JMS\Serializer\Annotation as JmsSerializer;
use EB\CommunicationBundle\Entity\Notification;
use EB\RideBundle\Entity\Car;
Use EB\RideBundle\Entity\Rating;
use EB\RideBundle\Entity\Ride;
use EB\RideBundle\Entity\RideRequest;

/**
 * User
 *
 * @ORM\Table(name="fos_user")
 * @ORM\Entity(repositoryClass="EB\UserBundle\Repository\UserRepository")
 * @JmsSerializer\ExclusionPolicy("all")
 */
class User extends BaseUser implements ParticipantInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @JmsSerializer\Expose
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
     * @ORM\Column(name="facebook_profile_link", type="string", length=255, nullable=true)
     */
    protected $facebookProfileLink;

    /**
     * @var string
     *
     * @ORM\Column(name="facebook_picture_link", type="string", length=255, nullable=true)
     */
    protected $facebookPictureLink;

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
     * @ORM\Column(name="google_profile_link", type="string", length=255, nullable=true)
     */
    protected $googleProfileLink;

    /**
     * @var string
     *
     * @ORM\Column(name="google_picture_link", type="string", length=255, nullable=true)
     */
    protected $googlePictureLink;

    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=255)
     * @JmsSerializer\Expose
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=255)
     * @JmsSerializer\Expose
     */
    private $lastname;

    /**
     * @var string
     *
     * @ORM\Column(name="county", type="string", length=255, nullable=true)
     */
    private $county;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255, nullable=true)
     * @JmsSerializer\Expose
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255, nullable=true)
     * @JmsSerializer\Expose
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255, nullable=true)
     * @JmsSerializer\Expose
     */
    private $phone;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birth_date", type="date", nullable=true)
     */
    private $birthDate;

    /**
     * @var string
     *
     * @ORM\Column(name="gender", type="string", length=255, nullable=true)
     */
    private $gender;

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

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="EB\RideBundle\Entity\Ride", mappedBy="user")
     */
    private $rides;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="EB\RideBundle\Entity\RideRequest", mappedBy="user")
     */
    private $rideRequests;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="EB\UserBundle\Entity\FriendRequest", mappedBy="sender")
     */
    private $friendRequests;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="EB\UserBundle\Entity\FriendRequest", mappedBy="receiver")
     */
    private $friendResponses;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="EB\CommunicationBundle\Entity\Notification", mappedBy="initiatorUser")
     */
    private $notificationsInitiated;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="EB\CommunicationBundle\Entity\Notification", mappedBy="receiverUser")
     */
    private $notificationsReceived;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="EB\RideBundle\Entity\Rating", mappedBy="awardingUser")
     */
    private $ratingsAwarded;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="EB\RideBundle\Entity\Rating", mappedBy="receiverUser")
     */
    private $ratingsReceived;


    public function __construct()
    {
        parent::__construct();
        $this->cars = new ArrayCollection();
        $this->rides = new ArrayCollection();
        $this->rideRequests = new ArrayCollection();
        $this->friendRequests = new ArrayCollection();
        $this->friendResponses = new ArrayCollection();
        $this->notificationsInitiated = new ArrayCollection();
        $this->notificationsReceived = new ArrayCollection();
        $this->ratingsAwarded = new ArrayCollection();
        $this->ratingsReceived = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->firstname . ' ' . $this->lastname;
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
     * @return string
     */
    public function getFullname()
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    /**
     * @return string
     */
    public function getFullnameAndUsername()
    {
        return $this->firstname . ' ' . $this->getLastname() . ' (' . $this->getUsername() . ')';
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
     * @param string $facebookProfileLink
     * @return $this
     */
    public function setFacebookProfileLink($facebookProfileLink)
    {
        $this->facebookProfileLink = $facebookProfileLink;

        return $this;
    }

    /**
     * @return string
     */
    public function getFacebookProfileLink()
    {
        return $this->facebookProfileLink;
    }

    /**
     * @param string $facebookPictureLink
     * @return $this
     */
    public function setFacebookPictureLink($facebookPictureLink)
    {
        $this->facebookPictureLink = $facebookPictureLink;

        return $this;
    }

    /**
     * @return string
     */
    public function getFacebookPictureLink()
    {
        return $this->facebookPictureLink;
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
     * @param string $googleProfileLink
     * @return $this
     */
    public function setGoogleProfileLink($googleProfileLink)
    {
        $this->googleProfileLink = $googleProfileLink;

        return $this;
    }

    /**
     * @return string
     */
    public function getGoogleProfileLink()
    {
        return $this->googleProfileLink;
    }

    /**
     * @param string $googlePictureLink
     * @return $this
     */
    public function setGooglePictureLink($googlePictureLink)
    {
        $this->googlePictureLink = $googlePictureLink;

        return $this;
    }

    /**
     * @return string
     */
    public function getGooglePictureLink()
    {
        return $this->googlePictureLink;
    }

    /**
     * @param string $county
     * @return $this
     */
    public function setCounty($county)
    {
        $this->county = $county;

        return $this;
    }

    /**
     * @return string
     */
    public function getCounty()
    {
        return $this->county;
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
     * @param string $gender
     * @return $this
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
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

    /**
     * @return ArrayCollection
     */
    public function getRides()
    {
        return $this->rides;
    }

    /**
     * @param Ride $ride
     * @return $this
     */
    public function addRide(Ride $ride)
    {
        $this->rides->add($ride);

        return $this;
    }

    /**
     * @param Ride $ride
     * @return $this
     */
    public function removeRide(Ride $ride)
    {
        $this->rides->removeElement($ride);

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getRideRequests()
    {
        return $this->rideRequests;
    }

    /**
     * @param RideRequest $rideRequest
     * @return $this
     */
    public function addRideRequest(RideRequest $rideRequest)
    {
        $this->rideRequests->add($rideRequest);

        return $this;
    }

    /**
     * @param RideRequest $rideRequest
     * @return $this
     */
    public function removeRideRequest(RideRequest $rideRequest)
    {
        $this->rideRequests->removeElement($rideRequest);

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getFriendRequests()
    {
        return $this->friendRequests;
    }

    /**
     * @param FriendRequest $friendRequest
     * @return $this
     */
    public function addFriendRequest(FriendRequest $friendRequest)
    {
        $this->friendRequests->add($friendRequest);

        return $this;
    }

    /**
     * @param FriendRequest $friendRequest
     * @return $this
     */
    public function removeFriendRequest(FriendRequest $friendRequest)
    {
        $this->friendRequests->removeElement($friendRequest);

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getFriendResponses()
    {
        return $this->friendResponses;
    }

    /**
     * @param FriendRequest $friendResponse
     * @return $this
     */
    public function addFriendResponse(FriendRequest $friendResponse)
    {
        $this->friendResponses->add($friendResponse);

        return $this;
    }

    /**
     * @param FriendRequest $friendResponse
     * @return $this
     */
    public function removeFriendResponse(FriendRequest $friendResponse)
    {
        $this->friendResponses->removeElement($friendResponse);

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getNotificationsInitiated()
    {
        return $this->notificationsInitiated;
    }

    /**
     * @param Notification $notification
     * @return $this
     */
    public function addNotificationInitiated(Notification $notification)
    {
        $this->notificationsInitiated->add($notification);

        return $this;
    }

    /**
     * @param Notification $notification
     * @return $this
     */
    public function removeNotificationInitiated(Notification $notification)
    {
        $this->notificationsInitiated->removeElement($notification);

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getNotificationsReceived()
    {
        return $this->notificationsReceived;
    }

    /**
     * @param Notification $notification
     * @return $this
     */
    public function addNotificationReceived(Notification $notification)
    {
        $this->notificationsReceived->add($notification);

        return $this;
    }

    /**
     * @param Notification $notification
     * @return $this
     */
    public function removeNotificationReceived(Notification $notification)
    {
        $this->notificationsReceived->removeElement($notification);

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getRatingsAwarded()
    {
        return $this->ratingsAwarded;
    }

    /**
     * @param Rating $rating
     * @return $this
     */
    public function addRatingAwarded(Rating $rating)
    {
        $this->ratingsAwarded->add($rating);

        return $this;
    }

    /**
     * @param Rating $rating
     * @return $this
     */
    public function removeRatingAwarded(Rating $rating)
    {
        $this->ratingsAwarded->removeElement($rating);

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getRatingsReceived()
    {
        return $this->ratingsReceived;
    }

    /**
     * @param Rating $rating
     * @return $this
     */
    public function addRatingReceived(Rating $rating)
    {
        $this->ratingsReceived->add($rating);

        return $this;
    }

    /**
     * @param Rating $rating
     * @return $this
     */
    public function removeRatingReceived(Rating $rating)
    {
        $this->ratingsReceived->removeElement($rating);

        return $this;
    }
}
