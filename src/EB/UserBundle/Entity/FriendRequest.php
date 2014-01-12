<?php

namespace EB\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FriendRequest
 *
 * @ORM\Table(name="friend_requests")
 * @ORM\Entity(repositoryClass="EB\UserBundle\Entity\FriendRequestRepository")
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
     * @ORM\Column(name="confirm_date", type="datetime", nullable=true)
     */
    private $confirmDate;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="EB\UserBundle\Entity\User", inversedBy="friendRequests")
     */
    private $sender;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="EB\UserBundle\Entity\User", inversedBy="friendConfirms")
     */
    private $receiver;

    /**
     * @var FriendRequestStatus
     *
     * @ORM\ManyToOne(targetEntity="EB\UserBundle\Entity\FriendRequestStatus", inversedBy="friendRequests")
     */
    private $status;


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
     * @param \DateTime $confirmDate
     * @return $this
     */
    public function setConfirmDate($confirmDate)
    {
        $this->confirmDate = $confirmDate;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getConfirmDate()
    {
        return $this->confirmDate;
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
}
