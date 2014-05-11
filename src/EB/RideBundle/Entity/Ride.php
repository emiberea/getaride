<?php

namespace EB\RideBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use EB\RideBundle\Entity\Car;
use EB\UserBundle\Entity\User;
use EB\MessageBundle\Entity\Thread;

/**
 * Ride
 *
 * @ORM\Table(name="rides")
 * @ORM\Entity(repositoryClass="EB\RideBundle\Repository\RideRepository")
 */
class Ride
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_date", type="datetime")
     */
    private $startDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="stop_date", type="datetime", nullable=true)
     */
    private $stopDate;

    /**
     * @var string
     *
     * @ORM\Column(name="start_location", type="string", length=255)
     */
    private $startLocation;

    /**
     * @var string
     *
     * @ORM\Column(name="stop_location", type="string", length=255)
     */
    private $stopLocation;

    /**
     * @var integer
     *
     * @ORM\Column(name="empty_seats_no", type="smallint")
     */
    private $emptySeatsNo;

    /**
     * @var integer
     *
     * @ORM\Column(name="baggage_per_seat", type="smallint")
     */
    private $baggagePerSeat;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="string", length=255)
     */
    private $comment;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_public", type="boolean")
     */
    private $isPublic;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="EB\UserBundle\Entity\User", inversedBy="rides")
     */
    private $user;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="RideRequest", mappedBy="ride")
     */
    private $rideRequests;

    /**
     * @var Car
     *
     * @ORM\ManyToOne(targetEntity="Car", inversedBy="rides")
     */
    private $car;

    /**
     * @var RideStatus
     *
     * @ORM\ManyToOne(targetEntity="RideStatus", inversedBy="rides")
     */
    private $rideStatus;

    /**
     * @var Thread
     *
     * @ORM\OneToOne(targetEntity="EB\MessageBundle\Entity\Thread", inversedBy="ride", cascade={"persist"})
     */
    private $thread;


    public function __construct()
    {
        $this->rideRequests = new ArrayCollection();
        $this->thread = new Thread();
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
     * Set startDate
     *
     * @param \DateTime $startDate
     * @return Ride
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime 
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set stopDate
     *
     * @param \DateTime $stopDate
     * @return Ride
     */
    public function setStopDate($stopDate)
    {
        $this->stopDate = $stopDate;

        return $this;
    }

    /**
     * Get stopDate
     *
     * @return \DateTime 
     */
    public function getStopDate()
    {
        return $this->stopDate;
    }

    /**
     * Set startLocation
     *
     * @param string $startLocation
     * @return Ride
     */
    public function setStartLocation($startLocation)
    {
        $this->startLocation = $startLocation;

        return $this;
    }

    /**
     * Get startLocation
     *
     * @return string 
     */
    public function getStartLocation()
    {
        return $this->startLocation;
    }

    /**
     * Set stopLocation
     *
     * @param string $stopLocation
     * @return Ride
     */
    public function setStopLocation($stopLocation)
    {
        $this->stopLocation = $stopLocation;

        return $this;
    }

    /**
     * Get stopLocation
     *
     * @return string 
     */
    public function getStopLocation()
    {
        return $this->stopLocation;
    }

    /**
     * Set emptySeatsNo
     *
     * @param integer $emptySeatsNo
     * @return Ride
     */
    public function setEmptySeatsNo($emptySeatsNo)
    {
        $this->emptySeatsNo = $emptySeatsNo;

        return $this;
    }

    /**
     * Get emptySeatsNo
     *
     * @return integer 
     */
    public function getEmptySeatsNo()
    {
        return $this->emptySeatsNo;
    }

    /**
     * Set baggagePerSeat
     *
     * @param integer $baggagePerSeat
     * @return Ride
     */
    public function setBaggagePerSeat($baggagePerSeat)
    {
        $this->baggagePerSeat = $baggagePerSeat;

        return $this;
    }

    /**
     * Get baggagePerSeat
     *
     * @return integer 
     */
    public function getBaggagePerSeat()
    {
        return $this->baggagePerSeat;
    }

    /**
     * Set comment
     *
     * @param string $comment
     * @return Ride
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string 
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param boolean $isPublic
     * @return $this
     */
    public function setIsPublic($isPublic)
    {
        $this->isPublic = $isPublic;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsPublic()
    {
        return $this->isPublic;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
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
     * @param Car $car
     * @return $this
     */
    public function setCar(Car $car)
    {
        $this->car = $car;

        return $this;
    }

    /**
     * @return Car
     */
    public function getCar()
    {
        return $this->car;
    }

    /**
     * @param RideStatus $rideStatus
     * @return $this
     */
    public function setRideStatus(RideStatus $rideStatus)
    {
        $this->rideStatus = $rideStatus;

        return $this;
    }

    /**
     * @return RideStatus
     */
    public function getRideStatus()
    {
        return $this->rideStatus;
    }

    /**
     * @param Thread $thread
     * @return $this
     */
    public function setThread(Thread $thread)
    {
        $this->thread = $thread;

        return $this;
    }

    /**
     * @return Thread
     */
    public function getThread()
    {
        return $this->thread;
    }
}
