<?php

namespace EB\RideBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * RideRequestStatus
 *
 * @ORM\Table(name="ride_request_statuses")
 * @ORM\Entity(repositoryClass="EB\RideBundle\Entity\RideRequestStatusRepository")
 */
class RideRequestStatus
{
    const REQUESTED = 1;
    const ACCEPTED = 2;

    public static $validStatuses = array(
        self::REQUESTED => 'Requested',
        self::ACCEPTED => 'Accepted',
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
     * @ORM\OneToMany(targetEntity="RideRequest", mappedBy="status")
     */
    private $rideRequests;


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
}
