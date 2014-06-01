<?php

namespace EB\RideBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;
use EB\UserBundle\Entity\User;
use EB\RideBundle\Entity\Ride;
use EB\RideBundle\Entity\RideRequest;
use EB\RideBundle\Entity\RideRequestStatus;

class RideExtension extends \Twig_Extension
{
    /** @var  ContainerInterface $container */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('get_ride_action', array($this, 'getRideAction')),
            new \Twig_SimpleFunction('get_accept_user_action', array($this, 'getAcceptUserAction')),
            new \Twig_SimpleFunction('get_baggage_per_seat', array($this, 'getBaggagePerSeat')),
        );
    }

    public function getName()
    {
        return 'eb_ride_extension';
    }

    /**
     * @param User $loggedUser
     * @param Ride $ride
     * @return array
     */
    public function getRideAction(User $loggedUser, Ride $ride)
    {
        $result = array();
        $existRelation = false;

        /** @var RideRequest[] $rideRequests */
        $rideRequests = $ride->getRideRequests();
        foreach ($rideRequests as $rideRequest) {
            if ($loggedUser == $rideRequest->getUser()) {
                if ($rideRequest->getStatus()->getId() == RideRequestStatus::REQUESTED) {
                    $result['text'] = 'Join Request Sent';
                    $result['btn_style'] = 'btn-warning';
                    $result['route'] = '#';
                    $existRelation = true;
                    break;
                } elseif ($rideRequest->getStatus()->getId() == RideRequestStatus::ACCEPTED) {
                    $result['text'] = 'Join Request Accepted';
                    $result['btn_style'] = 'btn-success';
                    $result['route'] = '#';
                    $existRelation = true;
                    break;
                }
            }
        }

        if ($existRelation == false) {
            $result['text'] = 'Send Join Request';
            $result['btn_style'] = 'btn-default';
            $result['route'] = $this->container->get('router')->generate('ride_request_send', array(
                'rideId' => $ride->getId(),
                'userId' => $loggedUser->getId(),
            ));
        }

        return $result;
    }

    /**
     * @param User $requesterUser
     * @param Ride $ride
     * @return array
     */
    public function getAcceptUserAction(User $requesterUser, Ride $ride)
    {
        $result = array();

        /** @var RideRequest[] $rideRequests */
        $rideRequests = $ride->getRideRequests();
        foreach ($rideRequests as $rideRequest) {
            if ($requesterUser == $rideRequest->getUser()) {
                if ($rideRequest->getStatus()->getId() == RideRequestStatus::REQUESTED) {
                    $result['text'] = 'Accept User';
                    $result['btn_style'] = 'btn-default';
                    $result['route'] = $this->container->get('router')->generate('ride_request_accept', array(
                        'id' => $rideRequest->getId(),
                    ));
                    break;
                } elseif ($rideRequest->getStatus()->getId() == RideRequestStatus::ACCEPTED) {
                    $result['text'] = 'User Accepted';
                    $result['btn_style'] = 'btn-success';
                    $result['route'] = '#';
                    break;
                }
            }
        }

        return $result;
    }

    /**
     * @param $baggagePerSeat
     * @return string
     */
    public function getBaggagePerSeat($baggagePerSeat)
    {
        switch ($baggagePerSeat) {
            case 0:
                $baggagePerSeatoStr = 'Without baggage';
                break;
            case 1:
                $baggagePerSeatoStr = 'Small';
                break;
            case 2:
                $baggagePerSeatoStr = 'Medium';
                break;
            case 3:
                $baggagePerSeatoStr = 'Large';
                break;
            case 4:
                $baggagePerSeatoStr = 'Extra-large';
                break;
            default:
                $baggagePerSeatoStr = 'Without baggage';
                break;
        }

        return $baggagePerSeatoStr;
    }
}
