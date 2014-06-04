<?php

namespace EB\RideBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use EB\UserBundle\Entity\User;
use EB\CommunicationBundle\Entity\Thread;
use EB\CommunicationBundle\Entity\Notification;

/**
 * RideRequest
 *
 * @ORM\Table(name="ride_requests")
 * @ORM\Entity(repositoryClass="EB\RideBundle\Repository\RideRequestRepository")
 */
class RideRequest
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
     * @ORM\Column(name="request_date", type="datetime")
     */
    private $requestDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="accept_date", type="datetime", nullable=true)
     */
    private $acceptDate;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="EB\UserBundle\Entity\User", inversedBy="rideRequests")
     */
    private $user;

    /**
     * @var Ride
     *
     * @ORM\ManyToOne(targetEntity="Ride", inversedBy="rideRequests")
     */
    private $ride;

    /**
     * @var RideRequestStatus
     *
     * @ORM\ManyToOne(targetEntity="RideRequestStatus", inversedBy="rideRequests")
     */
    private $status;

    /**
     * @var Thread
     *
     * @ORM\OneToOne(targetEntity="EB\CommunicationBundle\Entity\Thread", inversedBy="rideRequest")
     */
    private $thread;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="EB\CommunicationBundle\Entity\Notification", mappedBy="rideRequest")
     */
    private $notifications;


    public function __construct()
    {
        $this->notifications = new ArrayCollection();
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
     * @param \DateTime $requestDate
     * @return $this
     */
    public function setRequestDate($requestDate)
    {
        $this->requestDate = $requestDate;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getRequestDate()
    {
        return $this->requestDate;
    }

    /**
     * @param \DateTime $acceptDate
     * @return $this
     */
    public function setAcceptDate($acceptDate)
    {
        $this->acceptDate = $acceptDate;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getAcceptDate()
    {
        return $this->acceptDate;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function setUser($user)
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
     * @param Ride $ride
     * @return $this
     */
    public function setRide($ride)
    {
        $this->ride = $ride;

        return $this;
    }

    /**
     * @return Ride
     */
    public function getRide()
    {
        return $this->ride;
    }

    /**
     * @param RideRequestStatus $status
     * @return $this
     */
    public function setStatus(RideRequestStatus $status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return RideRequestStatus
     */
    public function getStatus()
    {
        return $this->status;
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

    /**
     * @return ArrayCollection
     */
    public function getNotifications()
    {
        return $this->notifications;
    }

    /**
     * @param Notification $notification
     * @return $this
     */
    public function addNotification(Notification $notification)
    {
        $this->notifications->add($notification);

        return $this;
    }

    /**
     * @param Notification $notification
     * @return $this
     */
    public function removeNotification(Notification $notification)
    {
        $this->notifications->removeElement($notification);

        return $this;
    }
}
