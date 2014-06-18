<?php

namespace EB\CommunicationBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Router;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\Collections\ArrayCollection;
use EB\CommunicationBundle\Entity\Notification;
use EB\CommunicationBundle\Event\NotificationEvent;
use EB\CommunicationBundle\Event\NotificationEvents;
use EB\CommunicationBundle\Service\MailerService;
use EB\UserBundle\Entity\FriendRequest;
use EB\RideBundle\Entity\Ride;
use EB\RideBundle\Entity\RideRequest;
use EB\RideBundle\Entity\RideRequestStatus;
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
            // RIDE_REQUEST
            NotificationEvents::RIDE_CANCELED => 'onRideCanceled',
            NotificationEvents::RIDE_CLOSED   => 'onRideClosed',
            // RATING
            NotificationEvents::RATING_AWARDED => 'onRatingAwarded',
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
        $notification->setRedirectUrl1($frSenderProfileUrl);
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
        $notification->setRedirectUrl1($frReceiverProfileUrl);
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
        $notification->setRedirectUrl1($requestingUsersUrl);
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
        $notification->setRedirectUrl1($publicRideUrl);
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

    public function onRideCanceled(NotificationEvent $event)
    {
        /** @var Ride $ride */
        $ride = $event->get('ride');

        // URLs
        $publicRideUrl = $this->router->generate('ride_show_public', array('id' => $ride->getId()), true);

        /** @var ArrayCollection|RideRequest[] $rideRequests */
        $rideRequests = $ride->getRideRequests();
        foreach ($rideRequests as $rideRequest) {
            if ($rideRequest->getStatus()->getId() == RideRequestStatus::ACCEPTED) {
                // creating notification and save it to the DB
                $notification = new Notification();
                $notification->setIsRead(false);
                $notification->setDate(new \DateTime());
                $notification->setRedirectUrl1($publicRideUrl);
                $notification->setType(Notification::TYPE_RIDE_CANCELED);
                $notification->setInitiatorUser($ride->getUser());
                $notification->setReceiverUser($rideRequest->getUser());
                $notification->setRideRequest($rideRequest);

                $this->em->persist($notification);
            }
        }

        $this->em->flush();
    }

    public function onRideClosed(NotificationEvent $event)
    {
        /** @var Ride $ride */
        $ride = $event->get('ride');

        // URLs
        $publicRideUrl = $this->router->generate('ride_show_public', array('id' => $ride->getId()), true);

        /** @var ArrayCollection|RideRequest[] $rideRequests */
        $rideRequests = $ride->getRideRequests();
        foreach ($rideRequests as $rideRequest) {
            if ($rideRequest->getStatus()->getId() == RideRequestStatus::ACCEPTED) {
                // creating notifications for the driver and the passenger and save them to the DB
                // notify the them that the ride is closer (over) and that they could give a rating score to each other
                $driverNotification = new Notification();
                $driverNotification->setIsRead(false);
                $driverNotification->setDate(new \DateTime());
                $driverNotification->setRedirectUrl1($publicRideUrl);
                $driverNotification->setRedirectUrl2(
                    $this->router->generate('ride_request_rating', array('id' => $rideRequest->getId()), true)
                );
                $driverNotification->setType(Notification::TYPE_RIDE_CLOSED);
                $driverNotification->setInitiatorUser($rideRequest->getUser());
                $driverNotification->setReceiverUser($ride->getUser());
                $driverNotification->setRideRequest($rideRequest);

                $passengerNotification = new Notification();
                $passengerNotification->setIsRead(false);
                $passengerNotification->setDate(new \DateTime());
                $passengerNotification->setRedirectUrl1($publicRideUrl);
                $passengerNotification->setRedirectUrl2(
                    $this->router->generate('ride_request_rating', array('id' => $rideRequest->getId()), true)
                );
                $passengerNotification->setType(Notification::TYPE_RIDE_CLOSED);
                $passengerNotification->setInitiatorUser($ride->getUser());
                $passengerNotification->setReceiverUser($rideRequest->getUser());
                $passengerNotification->setRideRequest($rideRequest);

                $this->em->persist($driverNotification);
                $this->em->persist($passengerNotification);
            }
        }

        $this->em->flush();
    }

    public function onRatingAwarded(NotificationEvent $event)
    {
        // the rideRequest and the 2 users: awardingUser, that awards the rating and receiverUser, that receives the rating
        /** @var RideRequest $rideRequest */
        $rideRequest = $event->get('ride_request');
        /** @var User $awardingUser */
        $awardingUser = $event->get('awarding_user');
        /** @var User $receiverUser */
        $receiverUser = $event->get('receiver_user');

        // URLs
        $receivedRatingUrl = $this->router->generate('rating_received', array(), true);

        // creating notification and save it to the DB
        // notify the user that he has received a rating and that he can view it
        $notification = new Notification();
        $notification->setIsRead(false);
        $notification->setDate(new \DateTime());
        $notification->setRedirectUrl1($receivedRatingUrl);
        $notification->setType(Notification::TYPE_RATING_AWARDED);
        $notification->setInitiatorUser($awardingUser);
        $notification->setReceiverUser($receiverUser);
        $notification->setRideRequest($rideRequest);

        $this->em->persist($notification);
        $this->em->flush();
    }
}
