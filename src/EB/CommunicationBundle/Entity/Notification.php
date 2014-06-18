<?php

namespace EB\CommunicationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use EB\RideBundle\Entity\RideRequest;
use EB\UserBundle\Entity\FriendRequest;
use EB\UserBundle\Entity\User;

/**
 * Notification
 *
 * @ORM\Table(name="notifications")
 * @ORM\Entity(repositoryClass="EB\CommunicationBundle\Repository\NotificationRepository")
 */
class Notification
{
    // TYPE_FRIEND_REQUEST
    const TYPE_FRIEND_REQUEST_SENT     = 1;
    const TYPE_FRIEND_REQUEST_ACCEPTED = 2;
    const TYPE_FRIEND_REQUEST_REJECTED = 3;
    // TYPE_RIDE_REQUEST
    const TYPE_RIDE_REQUEST_SENT     = 4;
    const TYPE_RIDE_REQUEST_ACCEPTED = 5;
    // TYPE_RIDE
    const TYPE_RIDE_CANCELED = 6;
    const TYPE_RIDE_CLOSED   = 7;
    // TYPE_RATING
    const TYPE_RATING_AWARDED = 8;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_read", type="boolean", options={"default":false})
     */
    private $isRead = false;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="redirect_url_1", type="string", length=255, nullable=true)
     */
    private $redirectUrl1;

    /**
     * @var string
     *
     * @ORM\Column(name="redirect_url_2", type="string", length=255, nullable=true)
     */
    private $redirectUrl2;

    /**
     * @var integer
     *
     * @ORM\Column(name="type", type="smallint")
     */
    private $type;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="EB\UserBundle\Entity\User", inversedBy="notificationsInitiated")
     */
    private $initiatorUser;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="EB\UserBundle\Entity\User", inversedBy="notificationsReceived")
     */
    private $receiverUser;

    /**
     * @var FriendRequest
     *
     * @ORM\ManyToOne(targetEntity="EB\UserBundle\Entity\FriendRequest", inversedBy="notifications")
     */
    private $friendRequest;

    /**
     * @var RideRequest
     *
     * @ORM\ManyToOne(targetEntity="EB\RideBundle\Entity\RideRequest", inversedBy="notifications")
     */
    private $rideRequest;


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
     * @param boolean $isRead
     * @return $this
     */
    public function setIsRead($isRead)
    {
        $this->isRead = $isRead;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsRead()
    {
        return $this->isRead;
    }

    /**
     * @param \DateTime $date
     * @return $this
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param string $redirectUrl1
     * @return $this
     */
    public function setRedirectUrl1($redirectUrl1)
    {
        $this->redirectUrl1 = $redirectUrl1;

        return $this;
    }

    /**
     * @return string
     */
    public function getRedirectUrl1()
    {
        return $this->redirectUrl1;
    }

    /**
     * @param string $redirectUrl2
     * @return $this
     */
    public function setRedirectUrl2($redirectUrl2)
    {
        $this->redirectUrl2 = $redirectUrl2;

        return $this;
    }

    /**
     * @return string
     */
    public function getRedirectUrl2()
    {
        return $this->redirectUrl2;
    }

    /**
     * @param int $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param User $initiatorUser
     * @return $this
     */
    public function setInitiatorUser($initiatorUser)
    {
        $this->initiatorUser = $initiatorUser;

        return $this;
    }

    /**
     * @return User
     */
    public function getInitiatorUser()
    {
        return $this->initiatorUser;
    }

    /**
     * @param User $receiverUser
     * @return $this
     */
    public function setReceiverUser($receiverUser)
    {
        $this->receiverUser = $receiverUser;

        return $this;
    }

    /**
     * @return User
     */
    public function getReceiverUser()
    {
        return $this->receiverUser;
    }

    /**
     * @param FriendRequest $friendRequest
     * @return $this
     */
    public function setFriendRequest($friendRequest)
    {
        $this->friendRequest = $friendRequest;

        return $this;
    }

    /**
     * @return FriendRequest
     */
    public function getFriendRequest()
    {
        return $this->friendRequest;
    }

    /**
     * @param RideRequest $rideRequest
     * @return $this
     */
    public function setRideRequest($rideRequest)
    {
        $this->rideRequest = $rideRequest;

        return $this;
    }

    /**
     * @return RideRequest
     */
    public function getRideRequest()
    {
        return $this->rideRequest;
    }
}
