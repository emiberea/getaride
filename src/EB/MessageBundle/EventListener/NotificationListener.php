<?php

namespace EB\MessageBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Router;
use EB\MessageBundle\Service\MailerService;
use EB\MessageBundle\Event\NotificationEvent;
use EB\MessageBundle\Event\NotificationEvents;
use EB\RideBundle\Entity\RideRequest;
use EB\UserBundle\Entity\User;

class NotificationListener implements EventSubscriberInterface
{
    /** @var Router $router */
    protected $router;

    /** @var MailerService $mailer */
    protected $mailer;

    public function __construct($router, $mailer)
    {
        $this->router = $router;
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents()
    {
        return array(
            // FRIEND_REQUEST
            NotificationEvents::FRIEND_REQUEST_SENT     => 'onFriendRequestSent',
            NotificationEvents::FRIEND_REQUEST_ACCEPTED => 'onFriendRequestAccepted',
            NotificationEvents::FRIEND_REQUEST_REJECTED => 'onFriendRequestRejected',
            // RIDE_REQUEST
            NotificationEvents::RIDE_REQUEST_SENT     => 'onRideRequestSent',
            NotificationEvents::RIDE_REQUEST_ACCEPTED => 'onRideRequestAccepted',
        );
    }

    public function onFriendRequestSent(NotificationEvent $event)
    {
        // friendRequest sender and receiver users
        /** @var User $frSender */
        $frSender = $event->get('fr_sender');
        /** @var User $frReceiver */
        $frReceiver = $event->get('fr_receiver');

        $to = $frReceiver->getEmail();
        $templatePath = ':Email:friendRequestSent.html.twig';
        $frSenderProfileUrl = $this->router->generate('eb_user_public_profile', array('username' => $frSender->getUsername()), true);

        $options = array(
            'fr_sender' => $frSender,
            'fr_receiver' => $frReceiver,
            'fr_sender_profile_url' => $frSenderProfileUrl,
        );

        $this->mailer->sendEmail($to, $templatePath, $options);
    }

    public function onFriendRequestAccepted(NotificationEvent $event)
    {
        // friendRequest sender and receiver users
        /** @var User $frSender */
        $frSender = $event->get('fr_sender');
        /** @var User $frReceiver */
        $frReceiver = $event->get('fr_receiver');

        $to = $frSender->getEmail();
        $templatePath = ':Email:friendRequestAccepted.html.twig';
        $frReceiverProfileUrl = $this->router->generate('eb_user_public_profile', array('username' => $frReceiver->getUsername()), true);

        $options = array(
            'fr_sender' => $frSender,
            'fr_receiver' => $frReceiver,
            'fr_receiver_profile_url' => $frReceiverProfileUrl,
        );

        $this->mailer->sendEmail($to, $templatePath, $options);
    }

    public function onRideRequestSent(NotificationEvent $event)
    {
        /** @var RideRequest $rideRequest */
        $rideRequest = $event->get('ride_request');

        // rideRequest sender (user that created the rideRequest) and receiver (user that created the ride) users
        /** @var User $rrSender */
        $rrSender = $rideRequest->getUser();
        /** @var User $rrReceiver */
        $rrReceiver = $rideRequest->getRide()->getUser();

        $to = $rrReceiver->getEmail();
        $templatePath = ':Email:rideRequestSent.html.twig';
        $rrSenderProfileUrl = $this->router->generate('eb_user_public_profile', array('username' => $rrSender->getUsername()), true);
        $publicRideUrl = $this->router->generate('ride_show_public', array('id' => $rideRequest->getRide()->getId()), true);
        $requestingUsersUrl = $this->router->generate('ride_show_requesting_users', array('id' => $rideRequest->getRide()->getId()), true);

        $options = array(
            'rr_sender' => $rrSender,
            'rr_receiver' => $rrReceiver,
            'ride' => $rideRequest->getRide(),
            'rr_sender_profile_url' => $rrSenderProfileUrl,
            'public_ride_url' => $publicRideUrl,
            'requesting_users_url' => $requestingUsersUrl,
        );

        $this->mailer->sendEmail($to, $templatePath, $options);
    }

    public function onRideRequestAccepted(NotificationEvent $event)
    {
        /** @var RideRequest $rideRequest */
        $rideRequest = $event->get('ride_request');

        // rideRequest sender (user that created the rideRequest) and receiver (user that created the ride) users
        /** @var User $rrSender */
        $rrSender = $rideRequest->getUser();
        /** @var User $rrReceiver */
        $rrReceiver = $rideRequest->getRide()->getUser();

        $to = $rrSender->getEmail();
        $templatePath = ':Email:rideRequestAccepted.html.twig';
        $rrReceiverProfileUrl = $this->router->generate('eb_user_public_profile', array('username' => $rrReceiver->getUsername()), true);
        $publicRideUrl = $this->router->generate('ride_show_public', array('id' => $rideRequest->getRide()->getId()), true);

        $options = array(
            'rr_sender' => $rrSender,
            'rr_receiver' => $rrReceiver,
            'ride' => $rideRequest->getRide(),
            'rr_receiver_profile_url' => $rrReceiverProfileUrl,
            'public_ride_url' => $publicRideUrl,
        );

        $this->mailer->sendEmail($to, $templatePath, $options);
    }
}
