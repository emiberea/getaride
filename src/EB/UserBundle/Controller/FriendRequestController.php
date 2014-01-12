<?php

namespace EB\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
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

        return $this->redirect($this->generateUrl('friend_index'));
    }

    /**
     * @Route("/confirm/{id}", name="friend_request_confirm")
     */
    public function confirmFriendRequest($id)
    {
        $em = $this->getDoctrine()->getManager();

        $friendRequest = $em->getRepository('EBUserBundle:FriendRequest')->find($id);
        $friendRequest->setConfirmDate(new \DateTime());
        $acceptedStatus = $em->getRepository('EBUserBundle:FriendRequestStatus')->find(FriendRequestStatus::CONFIRMED);
        $friendRequest->setStatus($acceptedStatus);

        $em->persist($friendRequest);
        $em->flush();

        return $this->redirect($this->generateUrl('friend_index'));
    }
}
