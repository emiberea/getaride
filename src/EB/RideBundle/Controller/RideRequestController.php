<?php

namespace EB\RideBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use EB\RideBundle\Entity\Ride;
use EB\RideBundle\Entity\RideRequest;
use EB\RideBundle\Entity\RideRequestStatus;

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
        $ride = $em->getRepository('EBRideBundle:Ride')->find($rideId);
        $user = $em->getRepository('EBUserBundle:User')->find($userId);

        if ($this->getUser() == $user) {
            $rideRequest = new RideRequest();
            $rideRequest->setRide($ride);
            $rideRequest->setUser($user);
            $rideRequest->setRequestDate(new \DateTime());
            $requestedStatus = $em->getRepository('EBRideBundle:RideRequestStatus')->find(RideRequestStatus::REQUESTED);
            $rideRequest->setStatus($requestedStatus);

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
        $em = $this->getDoctrine()->getManager();

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
}
