<?php

namespace EB\UserBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;
use EB\UserBundle\Entity\User;
use EB\UserBundle\Entity\FriendRequest;
use EB\UserBundle\Entity\FriendRequestStatus;
use EB\RideBundle\Entity\Ride;
use EB\RideBundle\Entity\RideRequest;
use EB\RideBundle\Entity\RideRequestStatus;

class UserExtension extends \Twig_Extension
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
            new \Twig_SimpleFunction('get_friend_action', array($this, 'getFriendAction')),
            new \Twig_SimpleFunction('get_ride_action', array($this, 'getRideAction')),
            new \Twig_SimpleFunction('get_accept_user_action', array($this, 'getAcceptUserAction')),
            new \Twig_SimpleFunction('get_baggage_per_seat', array($this, 'getBaggagePerSeat')),
        );
    }

    public function getName()
    {
        return 'eb_user_extension';
    }

    /**
     * @param User $loggedUser
     * @param User $viewedUser
     * @return array
     */
    public function getFriendAction(User $loggedUser, User $viewedUser)
    {
        $result = array();
        $existRelation = false;

        // case 1: FriendRequests that were SENT by the currently logged user
        /** @var FriendRequest $friendRequest */
        foreach ($loggedUser->getFriendRequests() as $friendRequest) {
            if ($loggedUser == $friendRequest->getSender() && $viewedUser == $friendRequest->getReceiver()) {
                if ($friendRequest->getStatus()->getId() == FriendRequestStatus::REQUESTED) {
                    $result['text'] = '+1 Friend Request Sent';
                    $result['btn_style'] = 'btn-warning';
                    $result['route'] = '#';
                    $existRelation = true;
                } elseif ($friendRequest->getStatus()->getId() == FriendRequestStatus::ACCEPTED) {
                    $result['text'] = 'Friends';
                    $result['btn_style'] = 'btn-success friend-remove';
                    $result['route'] = $this->container->get('router')->generate('friend_request_reject', array(
                        'id' => $friendRequest->getId(),
                    ));
                    $existRelation = true;
                } elseif ($friendRequest->getStatus()->getId() == FriendRequestStatus::REJECTED) {
                    $result['text'] = '+1 Add Friend';
                    $result['btn_style'] = 'btn-default';
                    $result['route'] = $this->container->get('router')->generate('friend_request_resend', array(
                        'id' => $friendRequest->getId(),
                        'senderId' => $loggedUser->getId(),
                        'receiverId' => $viewedUser->getId(),
                    ));
                    $existRelation = true;
                }
            }
        }

        // case 2: FriendRequests (friend responses) that were RECEIVED by the currently logged user
        /** @var FriendRequest $friendResponse */
        foreach ($loggedUser->getFriendResponses() as $friendResponse) {
            if ($loggedUser == $friendResponse->getReceiver() && $viewedUser == $friendResponse->getSender()) {
                if ($friendResponse->getStatus()->getId() == FriendRequestStatus::REQUESTED) {
                    $result['text'] = 'Confirm Request';
                    $result['btn_style'] = 'btn-warning';
                    $result['route'] = $this->container->get('router')->generate('friend_request_accept', array(
                        'id' => $friendResponse->getId(),
                    ));
                    $existRelation = true;
                } elseif ($friendResponse->getStatus()->getId() == FriendRequestStatus::ACCEPTED) {
                    $result['text'] = 'Friends';
                    $result['btn_style'] = 'btn-success friend-remove';
                    $result['route'] = $this->container->get('router')->generate('friend_request_reject', array(
                        'id' => $friendResponse->getId(),
                    ));
                    $existRelation = true;
                } elseif ($friendResponse->getStatus()->getId() == FriendRequestStatus::REJECTED) {
                    $result['text'] = '+1 Add Friend';
                    $result['btn_style'] = 'btn-default';
                    $result['route'] = $this->container->get('router')->generate('friend_request_resend', array(
                        'id' => $friendResponse->getId(),
                        'senderId' => $loggedUser->getId(),
                        'receiverId' => $viewedUser->getId(),
                    ));
                    $existRelation = true;
                }
            }
        }

        // case 3: between the 2 users it does not exist a FriendRequest, so we show the '+1 Add Friend' option
        if ($existRelation == false) {
            $result['text'] = '+1 Add Friend';
            $result['btn_style'] = 'btn-default';
            $result['route'] = $this->container->get('router')->generate('friend_request_send', array(
                'senderId' => $loggedUser->getId(),
                'receiverId' => $viewedUser->getId(),
            ));
        }

        return $result;
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
