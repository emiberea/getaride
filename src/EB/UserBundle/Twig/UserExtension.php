<?php

namespace EB\UserBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;
use EB\UserBundle\Entity\User;
use EB\UserBundle\Entity\FriendRequest;
use EB\UserBundle\Entity\FriendRequestStatus;

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
        );
    }

    public function getName()
    {
        return 'eb_user_extension';
    }

    /**
     * @param User $loggedUser
     * @param User $viewedUser
     * @return array $result
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
}
