<?php

namespace EB\CommunicationBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Router;
use Doctrine\ORM\EntityManager;
use EB\CommunicationBundle\Entity\Notification;
use EB\CommunicationBundle\Event\NotificationEvent;
use EB\CommunicationBundle\Event\NotificationEvents;
use EB\CommunicationBundle\Service\MailerService;
use EB\RideBundle\Entity\RideRequest;
use EB\UserBundle\Entity\FriendRequest;
use EB\UserBundle\Entity\User;

class NotificationListener implements EventSubscriberInterface
{
    /** @var Router $router */
    protected $router;

    /** @var EntityManager $em */
    protected $em;

    /** @var MailerService $mailer */
    protected $mailer;

    public function __construct($router, $em, $mailer)
    {
        $this->router = $router;
        $this->em = $em;
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
        /** @var FriendRequest $friendRequest */
        $friendRequest = $event->get('friend_request');

        // friendRequest sender and receiver users
        /** @var User $frSender */
        $frSender = $friendRequest->getSender();
        /** @var User $frReceiver */
        $frReceiver = $friendRequest->getReceiver();

        // URLs
        $frSenderProfileUrl = $this->router->generate('eb_user_public_profile', array('username' => $frSender->getUsername()), true);

        // creating notification and save it to the DB
        $notification = new Notification();
        $notification->setIsRead(false);
        $notification->setDate(new \DateTime());
        $notification->setRedirectUrl($frSenderProfileUrl);
        $notification->setType(Notification::TYPE_FRIEND_REQUEST_SENT);
        $notification->setInitiatorUser($frSender);
        $notification->setReceiverUser($frReceiver);
        $notification->setFriendRequest($friendRequest);

        $this->em->persist($notification);
        $this->em->flush();

        // sending email
        $to = $frReceiver->getEmail();
        $templatePath = ':Email:friendRequestSent.html.twig';
        $options = array(
            'fr_sender' => $frSender,
            'fr_receiver' => $frReceiver,
            'fr_sender_profile_url' => $frSenderProfileUrl,
        );

        $this->mailer->sendEmail($to, $templatePath, $options);
    }

    public function onFriendRequestAccepted(NotificationEvent $event)
    {
        /** @var FriendRequest $friendRequest */
        $friendRequest = $event->get('friend_request');

        // friendRequest sender and receiver users
        /** @var User $frSender */
        $frSender = $friendRequest->getSender();
        /** @var User $frReceiver */
        $frReceiver = $friendRequest->getReceiver();

        // URLs
        $frReceiverProfileUrl = $this->router->generate('eb_user_public_profile', array('username' => $frReceiver->getUsername()), true);

        // creating notification and save it to the DB
        $notification = new Notification();
        $notification->setIsRead(false);
        $notification->setDate(new \DateTime());
        $notification->setRedirectUrl($frReceiverProfileUrl);
        $notification->setType(Notification::TYPE_FRIEND_REQUEST_ACCEPTED);
        $notification->setInitiatorUser($frReceiver);
        $notification->setReceiverUser($frSender);
        $notification->setFriendRequest($friendRequest);

        $this->em->persist($notification);
        $this->em->flush();

        // sending email
        $to = $frSender->getEmail();
        $templatePath = ':Email:friendRequestAccepted.html.twig';
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

        // URLs
        $publicRideUrl = $this->router->generate('ride_show_public', array('id' => $rideRequest->getRide()->getId()), true);
        $requestingUsersUrl = $this->router->generate('ride_show_requesting_users', array('id' => $rideRequest->getRide()->getId()), true);

        // creating notification and save it to the DB
        $notification = new Notification();
        $notification->setIsRead(false);
        $notification->setDate(new \DateTime());
        $notification->setRedirectUrl($requestingUsersUrl);
        $notification->setType(Notification::TYPE_RIDE_REQUEST_SENT);
        $notification->setInitiatorUser($rrSender);
        $notification->setReceiverUser($rrReceiver);
        $notification->setRideRequest($rideRequest);

        $this->em->persist($notification);
        $this->em->flush();

        // sending mail
        $to = $rrReceiver->getEmail();
        $templatePath = ':Email:rideRequestSent.html.twig';
        $rrSenderProfileUrl = $this->router->generate('eb_user_public_profile', array('username' => $rrSender->getUsername()), true);
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

        // URLs
        $publicRideUrl = $this->router->generate('ride_show_public', array('id' => $rideRequest->getRide()->getId()), true);
        $rrReceiverProfileUrl = $this->router->generate('eb_user_public_profile', array('username' => $rrReceiver->getUsername()), true);

        // creating notification and save it to the DB
        $notification = new Notification();
        $notification->setIsRead(false);
        $notification->setDate(new \DateTime());
        $notification->setRedirectUrl($publicRideUrl);
        $notification->setType(Notification::TYPE_RIDE_REQUEST_ACCEPTED);
        $notification->setInitiatorUser($rrReceiver);
        $notification->setReceiverUser($rrSender);
        $notification->setRideRequest($rideRequest);

        $this->em->persist($notification);
        $this->em->flush();

        // sending mail
        $to = $rrSender->getEmail();
        $templatePath = ':Email:rideRequestAccepted.html.twig';
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
