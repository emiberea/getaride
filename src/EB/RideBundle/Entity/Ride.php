<?php

namespace EB\RideBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use EB\UserBundle\Entity\User;
use EB\RideBundle\Entity\Car;

/**
 * Ride
 *
 * @ORM\Table(name="rides")
 * @ORM\Entity(repositoryClass="EB\RideBundle\Entity\RideRepository")
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
     * @ORM\Column(name="stop_date", type="datetime")
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
     * @ORM\ManyToOne(targetEntity="EB\UserBundle\Entity\User", inversedBy="rides")
     */
    private $user;

    /**
     * @ORM\OneToOne(targetEntity="Car", inversedBy="ride", cascade={"persist"})
     */
    private $car;


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
}
