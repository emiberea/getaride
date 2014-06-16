<?php

namespace EB\RideBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use EB\RideBundle\Entity\Ride;
use EB\UserBundle\Entity\User;

/**
 * Car
 *
 * @ORM\Table(name="ratings")
 * @ORM\Entity(repositoryClass="EB\RideBundle\Repository\RatingRepository")
 */
class Rating
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
     * @var integer
     *
     * @ORM\Column(name="punctuality_mark", type="smallint")
     */
    private $punctualityMark;

    /**
     * @var integer
     *
     * @ORM\Column(name="agreement_mark", type="smallint")
     */
    private $agreementMark;

    /**
     * @var integer
     *
     * @ORM\Column(name="driving_mark", type="smallint")
     */
    private $drivingMark;

    /**
     * @var integer
     *
     * @ORM\Column(name="sociability_mark", type="smallint")
     */
    private $sociabilityMark;

    /**
     * @var integer
     *
     * @ORM\Column(name="music_mark", type="smallint")
     */
    private $musicMark;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="string", length=255, nullable=true)
     */
    private $comment;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="EB\UserBundle\Entity\User", inversedBy="ratingsAwarded")
     */
    private $awardingUser;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="EB\UserBundle\Entity\User", inversedBy="ratingsReceived")
     */
    private $receiverUser;

    /**
     * @var Ride
     *
     * @ORM\ManyToOne(targetEntity="Ride", inversedBy="ratings")
     */
    private $ride;


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
     * @param int $punctualityMark
     * @return $this
     */
    public function setPunctualityMark($punctualityMark)
    {
        $this->punctualityMark = $punctualityMark;

        return $this;
    }

    /**
     * @return int
     */
    public function getPunctualityMark()
    {
        return $this->punctualityMark;
    }

    /**
     * @param int $agreementMark
     * @return $this
     */
    public function setAgreementMark($agreementMark)
    {
        $this->agreementMark = $agreementMark;

        return $this;
    }

    /**
     * @return int
     */
    public function getAgreementMark()
    {
        return $this->agreementMark;
    }

    /**
     * @param int $drivingMark
     * @return $this
     */
    public function setDrivingMark($drivingMark)
    {
        $this->drivingMark = $drivingMark;

        return $this;
    }

    /**
     * @return int
     */
    public function getDrivingMark()
    {
        return $this->drivingMark;
    }

    /**
     * @param int $sociabilityMark
     * @return $this
     */
    public function setSociabilityMark($sociabilityMark)
    {
        $this->sociabilityMark = $sociabilityMark;

        return $this;
    }

    /**
     * @return int
     */
    public function getSociabilityMark()
    {
        return $this->sociabilityMark;
    }

    /**
     * @param int $musicMark
     * @return $this
     */
    public function setMusicMark($musicMark)
    {
        $this->musicMark = $musicMark;

        return $this;
    }

    /**
     * @return int
     */
    public function getMusicMark()
    {
        return $this->musicMark;
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
     * @param User $awardingUser
     * @return $this
     */
    public function setAwardingUser($awardingUser)
    {
        $this->awardingUser = $awardingUser;

        return $this;
    }

    /**
     * @return User
     */
    public function getAwardingUser()
    {
        return $this->awardingUser;
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
}
