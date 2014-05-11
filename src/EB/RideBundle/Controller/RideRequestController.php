<?php

namespace EB\RideBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;
use EB\RideBundle\Entity\Ride;
use EB\RideBundle\Entity\RideRequest;
use EB\RideBundle\Entity\RideRequestStatus;
use EB\UserBundle\Entity\User;

/**
 * RideRequest controller.
 *
 * @Route("/ride-request")
 */
class RideRequestController extends Controller
{
    /**
     * @Route("/send/{rideId}/{userId}", name="ride_request_send")
     */
    public function sendRideRequestAction($rideId, $userId)
    {
        $em = $this->getDoctrine()->getManager();

        // ride - ride that the user is willing to join, user - a user that want to join that ride
        /** @var Ride $ride */
        $ride = $em->getRepository('EBRideBundle:Ride')->find($rideId);
        /** @var User $user */
        $user = $em->getRepository('EBUserBundle:User')->find($userId);

        if ($this->getUser() == $user) {
            $rideRequest = new RideRequest();
            $rideRequest->setRide($ride);
            $rideRequest->setUser($user);
            $rideRequest->setRequestDate(new \DateTime());
            $requestedStatus = $em->getRepository('EBRideBundle:RideRequestStatus')->find(RideRequestStatus::REQUESTED);
            $rideRequest->setStatus($requestedStatus);

            // creating the message Thread for this RideRequest
            $msgComposer = $this->get('fos_message.composer');
            $threadBuilder = $msgComposer->newThread();
            $threadBuilder
                ->addRecipient($ride->getUser())
                ->setSender($this->getUser())
                ->setSubject(sprintf('[Ride request]: %s -> %s (%s to %s, on %s)',
                    $this->getUser(),
                    $ride->getUser(),
                    $rideRequest->getRide()->getStartLocation(),
                    $rideRequest->getRide()->getStopLocation(),
                    $rideRequest->getRide()->getStartDate()->format('d-m-Y')
                ))
                ->setBody(sprintf('Ride request sent by %s on %s', $this->getUser(), $rideRequest->getRequestDate()->format('d-m-Y H:i:s')));

            $msgSender = $this->get('fos_message.sender');
            $msgSender->send($threadBuilder->getMessage());

            $rideRequest->setThread($threadBuilder->getMessage()->getThread());

            $em->persist($rideRequest);
            $em->flush();

            return $this->redirect($this->generateUrl('ride_show_public', array(
                'id' => $rideId,
            )));
        }

        throw $this->createNotFoundException('Not Found. Wrong URL.');
    }

    /**
     * @Route("/{id}/accept", name="ride_request_accept")
     */
    public function acceptFriendRequest($id)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        /** @var RideRequest $rideRequest */
        $rideRequest = $em->getRepository('EBRideBundle:RideRequest')->find($id);

        if ($this->getUser() == $rideRequest->getRide()->getUser()) {
            $rideRequest->setAcceptDate(new \DateTime());
            $acceptedStatus = $em->getRepository('EBRideBundle:RideRequestStatus')->find(RideRequestStatus::ACCEPTED);
            $rideRequest->setStatus($acceptedStatus);

            $em->persist($rideRequest);
            $em->flush();

            return $this->redirect($this->generateUrl('ride_show_requesting_users', array(
                'id' => $rideRequest->getRide()->getId(),
            )));
        }

        throw $this->createNotFoundException('Not Found. Wrong URL.');
    }

    /**
     * @Route("/attempted", name="ride_attempted")
     * @Template()
     */
    public function showAttemptedRidesAction()
    {
        $em = $this->getDoctrine()->getManager();

        $userId = $this->getUser()->getId();

        $dql = "SELECT rr FROM EBRideBundle:RideRequest rr
                WHERE rr.user = '$userId'";
        $query = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $this->get('request')->query->get('page', 1),
            10
        );

        return array(
            'pagination' => $pagination,
        );
    }

    /**
     * @Route("/{id}/chat", name="ride_request_chat")
     */
    public function chatAction($id)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        /** @var RideRequest $rideRequest */
        $rideRequest = $em->getRepository('EBRideBundle:RideRequest')->find($id);

        return $this->redirect($this->generateUrl('fos_message_thread_view', array(
            'threadId' => $rideRequest->getThread()->getId(),
        )));
    }
}
