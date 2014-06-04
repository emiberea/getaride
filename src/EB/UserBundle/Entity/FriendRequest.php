<?php

namespace EB\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use EB\CommunicationBundle\Entity\Notification;

/**
 * FriendRequest
 *
 * @ORM\Table(name="friend_requests")
 * @ORM\Entity(repositoryClass="EB\UserBundle\Repository\FriendRequestRepository")
 */
class FriendRequest
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
     * @var \DateTime
     *
     * @ORM\Column(name="reject_date", type="datetime", nullable=true)
     */
    private $rejectDate;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="EB\UserBundle\Entity\User", inversedBy="friendRequests")
     */
    private $sender;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="EB\UserBundle\Entity\User", inversedBy="friendResponses")
     */
    private $receiver;

    /**
     * @var FriendRequestStatus
     *
     * @ORM\ManyToOne(targetEntity="EB\UserBundle\Entity\FriendRequestStatus", inversedBy="friendRequests")
     */
    private $status;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="EB\CommunicationBundle\Entity\Notification", mappedBy="friendRequest")
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
     * @param \DateTime $rejectDate
     * @return $this
     */
    public function setRejectDate($rejectDate)
    {
        $this->rejectDate = $rejectDate;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getRejectDate()
    {
        return $this->rejectDate;
    }

    /**
     * @param User $sender
     * @return $this
     */
    public function setSender(User $sender)
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * @return User
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * @param User $receiver
     * @return $this
     */
    public function setReceiver(User $receiver)
    {
        $this->receiver = $receiver;

        return $this;
    }

    /**
     * @return User
     */
    public function getReceiver()
    {
        return $this->receiver;
    }

    /**
     * @param FriendRequestStatus $status
     * @return $this
     */
    public function setStatus(FriendRequestStatus $status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return FriendRequestStatus
     */
    public function getStatus()
    {
        return $this->status;
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
