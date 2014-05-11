<?php

namespace EB\MessageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\MessageBundle\Entity\Thread as BaseThread;
use EB\RideBundle\Entity\RideRequest;

/**
 * @ORM\Table(name="fos_thread")
 * @ORM\Entity
 */
class Thread extends BaseThread
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="EB\UserBundle\Entity\User")
     */
    protected $createdBy;

    /**
     * @var Message[]|\Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="EB\MessageBundle\Entity\Message", mappedBy="thread")
     */
    protected $messages;

    /**
     * @var ThreadMetadata[]|\Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="EB\MessageBundle\Entity\ThreadMetadata", mappedBy="thread", cascade={"all"})
     */
    protected $metadata;

    /**
     * @var RideRequest
     *
     * @ORM\OneToOne(targetEntity="EB\RideBundle\Entity\RideRequest", mappedBy="thread")
     */
    private $rideRequest;


    /**
     * @param RideRequest $rideRequest
     * @return $this
     */
    public function setRideRequest(RideRequest $rideRequest)
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
