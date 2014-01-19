<?php

namespace EB\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
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
        $em = $this->getDoctrine()->getManager();

        // sender - logged user, receiver - the user that has the profile viewed by the logged user
        $sender = $em->getRepository('EBUserBundle:User')->find($senderId);
        $receiver = $em->getRepository('EBUserBundle:User')->find($receiverId);

        if ($this->getUser() == $sender) {
            $friendRequest = new FriendRequest();
            $friendRequest->setSender($sender);
            $friendRequest->setReceiver($receiver);
            $friendRequest->setRequestDate(new \DateTime());
            $requestedStatus = $em->getRepository('EBUserBundle:FriendRequestStatus')->find(FriendRequestStatus::REQUESTED);
            $friendRequest->setStatus($requestedStatus);

            $em->persist($friendRequest);
            $em->flush();

            return new Response('sent-ok');
        }

        throw $this->createNotFoundException('Not Found. Wrong URL.');
    }

    /**
     * @Route("/accept/{id}", name="friend_request_accept")
     */
    public function acceptFriendRequest($id)
    {
        $em = $this->getDoctrine()->getManager();

        $friendRequest = $em->getRepository('EBUserBundle:FriendRequest')->find($id);

        if ($this->getUser() == $friendRequest->getReceiver()) {
            $friendRequest->setAcceptDate(new \DateTime());
            $acceptedStatus = $em->getRepository('EBUserBundle:FriendRequestStatus')->find(FriendRequestStatus::ACCEPTED);
            $friendRequest->setStatus($acceptedStatus);

            $em->persist($friendRequest);
            $em->flush();

            return new Response('accept-ok');
        }

        throw $this->createNotFoundException('Not Found. Wrong URL.');
    }

    /**
     * @Route("/reject/{id}", name="friend_request_reject")
     */
    public function rejectFriendRequest($id)
    {
        $em = $this->getDoctrine()->getManager();

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
    public function resendFriendRequest($id, $senderId, $receiverId)
    {
        $em = $this->getDoctrine()->getManager();

        // sender - logged user, receiver - the user that has the profile viewed by the logged user
        $sender = $em->getRepository('EBUserBundle:User')->find($senderId);
        $receiver = $em->getRepository('EBUserBundle:User')->find($receiverId);

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

            return new Response('resend-ok');
        }

        throw $this->createNotFoundException('Not Found. Wrong URL.');
    }
}
