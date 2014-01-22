<?php

namespace EB\RideBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * RideStatus
 *
 * @ORM\Table(name="ride_statuses")
 * @ORM\Entity(repositoryClass="EB\RideBundle\Entity\RideStatusRepository")
 */
class RideStatus
{
    const DRAFT          = 1;
    const AVAILABLE      = 2;
    const CANCELED       = 3;
    const FINISH_FAIL    = 4;
    const FINISH_SUCCESS = 5;

    public static $validStatuses = array(
        self::DRAFT          => 'Draft',
        self::AVAILABLE      => 'Available',
        self::CANCELED       => 'Canceled',
        self::FINISH_FAIL    => 'Finish Fail',
        self::FINISH_SUCCESS => 'Finish Success',
    );

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Ride", mappedBy="rideStatus")
     */
    private $rides;


    public function __toString()
    {
        return $this->name;
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
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
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
}
