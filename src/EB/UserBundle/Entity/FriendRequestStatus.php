<?php

namespace EB\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * FriendRequestStatus
 *
 * @ORM\Table(name="friend_request_statuses")
 * @ORM\Entity(repositoryClass="EB\UserBundle\Entity\FriendRequestStatusRepository")
 */
class FriendRequestStatus
{
    const REQUESTED = 1;
    const ACCEPTED = 2;
    const REJECTED = 3;

    public static $validStatuses = array(
        self::REQUESTED => 'Requested',
        self::ACCEPTED => 'Accepted',
        self::REJECTED => 'Rejected',
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
     * @ORM\OneToMany(targetEntity="EB\UserBundle\Entity\FriendRequest", mappedBy="status")
     */
    private $friendRequests;

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
    public function getFriendRequests()
    {
        return $this->friendRequests;
    }

    /**
     * @param FriendRequest $friendRequest
     * @return $this
     */
    public function addRide(FriendRequest $friendRequest)
    {
        $this->friendRequests->add($friendRequest);

        return $this;
    }

    /**
     * @param FriendRequest $friendRequest
     * @return $this
     */
    public function removeRide(FriendRequest $friendRequest)
    {
        $this->friendRequests->removeElement($friendRequest);

        return $this;
    }
}
