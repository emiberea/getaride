<?php

namespace EB\RideBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use EB\UserBundle\Entity\User;
use EB\RideBundle\Entity\Ride;

/**
 * Car
 *
 * @ORM\Table(name="cars")
 * @ORM\Entity(repositoryClass="EB\RideBundle\Repository\CarRepository")
 */
class Car
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
     * @var string
     *
     * @ORM\Column(name="brand", type="string", length=255)
     */
    private $brand;

    /**
     * @var string
     *
     * @ORM\Column(name="model", type="string", length=255)
     */
    private $model;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="build_year", type="date")
     */
    private $buildYear;

    /**
     * @var string
     *
     * @ORM\Column(name="number_plate", type="string", length=255, nullable=true)
     */
    private $numberPlate;

    /**
     * @var string
     *
     * @ORM\Column(name="color", type="string", length=255, nullable=true)
     */
    private $color;

    /**
     * @var boolean
     *
     * @ORM\Column(name="has_air_conditioning", type="boolean", nullable=true)
     */
    private $hasAirConditioning;

    /**
     * @ORM\ManyToOne(targetEntity="EB\UserBundle\Entity\User", inversedBy="cars")
     */
    private $user;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Ride", mappedBy="car")
     */
    private $rides;


    public function __toString()
    {
        return (string) $this->brand . ' ' . $this->model;
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
     * @param string $brand
     * @return $this
     */
    public function setBrand($brand)
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * @return string
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * @param string $model
     * @return $this
     */
    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param \DateTime $buildYear
     * @return $this
     */
    public function setBuildYear($buildYear)
    {
        $this->buildYear = $buildYear;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getBuildYear()
    {
        return $this->buildYear;
    }

    /**
     * @param string $numberPlate
     * @return $this
     */
    public function setNumberPlate($numberPlate)
    {
        $this->numberPlate = $numberPlate;

        return $this;
    }

    /**
     * @return string
     */
    public function getNumberPlate()
    {
        return $this->numberPlate;
    }

    /**
     * @param string $color
     * @return $this
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param boolean $hasAirConditioning
     * @return $this
     */
    public function setHasAirConditioning($hasAirConditioning)
    {
        $this->hasAirConditioning = $hasAirConditioning;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getHasAirConditioning()
    {
        return $this->hasAirConditioning;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function setUser(User $user)
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
     * @return ArrayCollection
     */
    public function getRides()
    {
        return $this->rides;
    }

    /**
     * @param Ride $ride
     * @return $this
     */
    public function addRide(Ride $ride)
    {
        $this->rides->add($ride);

        return $this;
    }

    /**
     * @param Ride $ride
     * @return $this
     */
    public function removeRide(Ride $ride)
    {
        $this->rides->removeElement($ride);

        return $this;
    }
}
