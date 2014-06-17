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
     * @ORM\Column(name="punctuality_score", type="smallint")
     */
    private $punctualityScore;

    /**
     * @var integer
     *
     * @ORM\Column(name="agreement_score", type="smallint")
     */
    private $agreementScore;

    /**
     * @var integer
     *
     * @ORM\Column(name="driving_score", type="smallint")
     */
    private $drivingScore;

    /**
     * @var integer
     *
     * @ORM\Column(name="sociability_score", type="smallint")
     */
    private $sociabilityScore;

    /**
     * @var integer
     *
     * @ORM\Column(name="music_score", type="smallint")
     */
    private $musicScore;

    /**
     * @var float
     *
     * @ORM\Column(name="total_score", type="decimal", precision=5, scale=2)
     */
    private $totalScore;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="string", length=255, nullable=true)
     */
    private $comment;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

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
     * @var RideRequest
     *
     * @ORM\ManyToOne(targetEntity="RideRequest", inversedBy="ratings")
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
     * @param int $punctualityScore
     * @return $this
     */
    public function setPunctualityScore($punctualityScore)
    {
        $this->punctualityScore = $punctualityScore;

        return $this;
    }

    /**
     * @return int
     */
    public function getPunctualityScore()
    {
        return $this->punctualityScore;
    }

    /**
     * @param int $agreementScore
     * @return $this
     */
    public function setAgreementScore($agreementScore)
    {
        $this->agreementScore = $agreementScore;

        return $this;
    }

    /**
     * @return int
     */
    public function getAgreementScore()
    {
        return $this->agreementScore;
    }

    /**
     * @param int $drivingScore
     * @return $this
     */
    public function setDrivingScore($drivingScore)
    {
        $this->drivingScore = $drivingScore;

        return $this;
    }

    /**
     * @return int
     */
    public function getDrivingScore()
    {
        return $this->drivingScore;
    }

    /**
     * @param int $sociabilityScore
     * @return $this
     */
    public function setSociabilityScore($sociabilityScore)
    {
        $this->sociabilityScore = $sociabilityScore;

        return $this;
    }

    /**
     * @return int
     */
    public function getSociabilityScore()
    {
        return $this->sociabilityScore;
    }

    /**
     * @param int $musicScore
     * @return $this
     */
    public function setMusicScore($musicScore)
    {
        $this->musicScore = $musicScore;

        return $this;
    }

    /**
     * @return int
     */
    public function getMusicScore()
    {
        return $this->musicScore;
    }

    /**
     * @param float $totalScore
     * @return $this
     */
    public function setTotalScore($totalScore)
    {
        $this->totalScore = $totalScore;

        return $this;
    }

    /**
     * @return float
     */
    public function getTotalScore()
    {
        return $this->totalScore;
    }

    /**
     * @param string $comment
     * @return $this
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
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
