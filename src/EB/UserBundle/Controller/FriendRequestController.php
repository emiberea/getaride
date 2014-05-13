<?php

namespace EB\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;
use EB\RideBundle\Event\NotificationEvent;
use EB\RideBundle\Event\NotificationEvents;
use EB\UserBundle\Entity\FriendRequest;
use EB\UserBundle\Entity\FriendRequestStatus;

/**
 * FriendRequest controller.
 *
 * @Route("/friend-request")
 */
class FriendRequestController extends Controller
{
    /**
     * @Route("/send/{senderId}/{receiverId}", name="friend_request_send")
     */
    public function sendFriendRequestAction($senderId, $receiverId)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        // sender - logged user, receiver - the user that has the profile viewed by the logged user
        $sender = $em->getRepository('EBUserBundle:User')->find($senderId);
        $receiver = $em->getRepository('EBUserBundle:User')->find($receiverId);

        if ($this->getUser() == $sender) {
            /** @var FriendRequest $friendRequest */
            $friendRequest = new FriendRequest();
            $friendRequest->setSender($sender);
            $friendRequest->setReceiver($receiver);
            $friendRequest->setRequestDate(new \DateTime());
            $requestedStatus = $em->getRepository('EBUserBundle:FriendRequestStatus')->find(FriendRequestStatus::REQUESTED);
            $friendRequest->setStatus($requestedStatus);

            $em->persist($friendRequest);
            $em->flush();

            // dispatching the FRIEND_REQUEST_SENT event, which triggers the listener to send also a mail to the receiver user of that friendRequest
            $dispatcher = $this->get('event_dispatcher');
            $dispatcher->dispatch(NotificationEvents::FRIEND_REQUEST_SENT, new NotificationEvent(array(
                'fr_sender' => $sender,
                'fr_receiver' => $receiver,
            )));

            return new Response('sent-ok');
        }

        throw $this->createNotFoundException('Not Found. Wrong URL.');
    }

    /**
     * @Route("/accept/{id}", name="friend_request_accept")
     */
    public function acceptFriendRequestAction($id)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        /** @var FriendRequest $friendRequest */
        $friendRequest = $em->getRepository('EBUserBundle:FriendRequest')->find($id);

        if ($this->getUser() == $friendRequest->getReceiver()) {
            $friendRequest->setAcceptDate(new \DateTime());
            $acceptedStatus = $em->getRepository('EBUserBundle:FriendRequestStatus')->find(FriendRequestStatus::ACCEPTED);
            $friendRequest->setStatus($acceptedStatus);

            $em->persist($friendRequest);
            $em->flush();

            // dispatching the FRIEND_REQUEST_ACCEPTED event, which triggers the listener to send also a mail to the receiver user of that friendRequest
            $dispatcher = $this->get('event_dispatcher');
            $dispatcher->dispatch(NotificationEvents::FRIEND_REQUEST_ACCEPTED, new NotificationEvent(array(
                'fr_sender' => $friendRequest->getSender(),
                'fr_receiver' => $friendRequest->getReceiver(),
            )));

            return new Response('accept-ok');
        }

        throw $this->createNotFoundException('Not Found. Wrong URL.');
    }

    /**
     * @Route("/reject/{id}", name="friend_request_reject")
     */
    public function rejectFriendRequestAction($id)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        /** @var FriendRequest $friendRequest */
        $friendRequest = $em->getRepository('EBUserBundle:FriendRequest')->find($id);

        if ($this->getUser() == $friendRequest->getSender() || $this->getUser() == $friendRequest->getReceiver()) {
            $friendRequest->setRejectDate(new \DateTime());
            $rejectedStatus = $em->getRepository('EBUserBundle:FriendRequestStatus')->find(FriendRequestStatus::REJECTED);
            $friendRequest->setStatus($rejectedStatus);

            $em->persist($friendRequest);
            $em->flush();

            return new Response('reject-ok');
        }

        throw $this->createNotFoundException('Not Found. Wrong URL.');
    }

    /**
     * @Route("/resend/{id}/{senderId}/{receiverId}", name="friend_request_resend")
     */
    public function resendFriendRequestAction($id, $senderId, $receiverId)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        // sender - logged user, receiver - the user that has the profile viewed by the logged user
        $sender = $em->getRepository('EBUserBundle:User')->find($senderId);
        $receiver = $em->getRepository('EBUserBundle:User')->find($receiverId);

        /** @var FriendRequest $friendRequest */
        $friendRequest = $em->getRepository('EBUserBundle:FriendRequest')->find($id);

        if ($this->getUser() == $sender) {
            $friendRequest->setSender($sender);
            $friendRequest->setReceiver($receiver);
            $friendRequest->setRequestDate(new \DateTime());
            $friendRequest->setAcceptDate(null);
            $friendRequest->setRejectDate(null);
            $requestedStatus = $em->getRepository('EBUserBundle:FriendRequestStatus')->find(FriendRequestStatus::REQUESTED);
            $friendRequest->setStatus($requestedStatus);

            $em->persist($friendRequest);
            $em->flush();

            // dispatching the FRIEND_REQUEST_SENT event, which triggers the listener to send also a mail to the receiver user of that friendRequest
            $dispatcher = $this->get('event_dispatcher');
            $dispatcher->dispatch(NotificationEvents::FRIEND_REQUEST_SENT, new NotificationEvent(array(
                'fr_sender' => $sender,
                'fr_receiver' => $receiver,
            )));

            return new Response('resend-ok');
        }

        throw $this->createNotFoundException('Not Found. Wrong URL.');
    }
}
