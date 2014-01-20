<?php

namespace EB\MessageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\MessageBundle\Entity\Thread as BaseThread;
use EB\RideBundle\Entity\Ride;

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
     * @var Ride
     *
     * @ORM\OneToOne(targetEntity="EB\RideBundle\Entity\Ride", mappedBy="thread")
     */
    private $ride;


    /**
     * @param Ride $ride
     * @return $this
     */
    public function setRide(Ride $ride)
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
}
