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

        $sender = $em->getRepository('EBUserBundle:User')->find($senderId);
        $receiver = $em->getRepository('EBUserBundle:User')->find($receiverId);

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

    /**
     * @Route("/accept/{id}", name="friend_request_accept")
     */
    public function acceptFriendRequest($id)
    {
        $em = $this->getDoctrine()->getManager();

        $friendRequest = $em->getRepository('EBUserBundle:FriendRequest')->find($id);
        $friendRequest->setAcceptDate(new \DateTime());
        $acceptedStatus = $em->getRepository('EBUserBundle:FriendRequestStatus')->find(FriendRequestStatus::ACCEPTED);
        $friendRequest->setStatus($acceptedStatus);

        $em->persist($friendRequest);
        $em->flush();

        return new Response('accept-ok');
    }

    /**
     * @Route("/reject/{id}", name="friend_request_reject")
     */
    public function rejectFriendRequest($id)
    {
        $em = $this->getDoctrine()->getManager();

        $friendRequest = $em->getRepository('EBUserBundle:FriendRequest')->find($id);
        $friendRequest->setRejectDate(new \DateTime());
        $rejectedStatus = $em->getRepository('EBUserBundle:FriendRequestStatus')->find(FriendRequestStatus::REJECTED);
        $friendRequest->setStatus($rejectedStatus);

        $em->persist($friendRequest);
        $em->flush();

        return new Response('reject-ok');
    }

    /**
     * @Route("/resend/{id}/{senderId}/{receiverId}", name="friend_request_resend")
     */
    public function resendFriendRequest($id, $senderId, $receiverId)
    {
        $em = $this->getDoctrine()->getManager();

        $sender = $em->getRepository('EBUserBundle:User')->find($senderId);
        $receiver = $em->getRepository('EBUserBundle:User')->find($receiverId);

        $friendRequest = $em->getRepository('EBUserBundle:FriendRequest')->find($id);
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
}
