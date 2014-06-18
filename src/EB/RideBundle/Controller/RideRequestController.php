<?php

namespace EB\RideBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;
use EB\CommunicationBundle\Event\NotificationEvent;
use EB\CommunicationBundle\Event\NotificationEvents;
use EB\RideBundle\Entity\Rating;
use EB\RideBundle\Entity\Ride;
use EB\RideBundle\Entity\RideRequest;
use EB\RideBundle\Entity\RideRequestStatus;
use EB\UserBundle\Entity\User;
use EB\RideBundle\Form\RatingType;

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
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        // ride - ride that the user is willing to join, user - a user that want to join that ride
        /** @var Ride $ride */
        $ride = $em->getRepository('EBRideBundle:Ride')->find($rideId);
        /** @var User $user */
        $user = $em->getRepository('EBUserBundle:User')->find($userId);

        if ($this->getUser() == $user) {
            // check if a ride request exists between the ride and the user
            /** @var RideRequest $rideRequest */
            $rideRequest = $em->getRepository('EBRideBundle:RideRequest')->findByRideAndUser($ride, $user);
            if ($rideRequest) {
                $this->get('session')->getFlashBag()->add(
                    'error',
                    'You already created a request for this ride!'
                );
                return $this->redirect($this->generateUrl('ride_show_public', array(
                    'id' => $rideId,
                )));
            } else {
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

                // dispatching the RIDE_REQUEST_SENT event, which triggers the listener to send also a mail to the user that created the ride
                $dispatcher = $this->get('event_dispatcher');
                $dispatcher->dispatch(NotificationEvents::RIDE_REQUEST_SENT, new NotificationEvent(array(
                    'ride_request' => $rideRequest,
                )));

                return $this->redirect($this->generateUrl('ride_show_public', array(
                    'id' => $rideId,
                )));
            }
        }

        throw $this->createNotFoundException('Not Found. Wrong URL.');
    }

    /**
     * @Route("/{id}/accept", name="ride_request_accept")
     */
    public function acceptRideRequestAction($id)
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

            // dispatching the RIDE_REQUEST_ACCEPTED event, which triggers the listener to send also a mail to the user that created the rideRequest
            $dispatcher = $this->get('event_dispatcher');
            $dispatcher->dispatch(NotificationEvents::RIDE_REQUEST_ACCEPTED, new NotificationEvent(array(
                'ride_request' => $rideRequest,
            )));

            return $this->redirect($this->generateUrl('ride_show_requesting_users', array(
                'id' => $rideRequest->getRide()->getId(),
            )));
        }

        throw $this->createNotFoundException('Not Found. Wrong URL.');
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

    /**
     * @Route("/{id}/rating", name="ride_request_rating")
     * @Template()
     */
    public function ratingAction(Request $request, $id)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        /** @var RideRequest $rideRequest */
        $rideRequest = $em->getRepository('EBRideBundle:RideRequest')->findOneBy(array(
            'id' => $id,
        ));
        // check if the rideRequest exists
        if (!$rideRequest) {
            throw $this->createNotFoundException('Unable to find RideRequest entity.');
        }

        if ($this->getUser() == $rideRequest->getRide()->getUser()) {
            // case the logged user is the one that created the ride

            // check if the user that created the ride has already given the rating to the user that created this rideRequest
            $rating = $em->getRepository('EBRideBundle:Rating')->findOneBy(array(
                'awardingUser' => $this->getUser(),
                'receiverUser' => $rideRequest->getUser(),
                'rideRequest' => $rideRequest,
            ));
            // check if the rating was already given
            if ($rating || $rideRequest->getStatus()->getId() == RideRequestStatus::REQUESTED) {
                $this->get('session')->getFlashBag()->add(
                    'warning',
                    'You can not give a rating score to this user!'
                );
                return $this->redirect($this->generateUrl('ride_show_requesting_users', array(
                    'id' => $rideRequest->getRide()->getId()
                )));
            }

            // creating the new rating and save it to the DB
            /** @var Rating $rating */
            $rating = new Rating();

            $form = $this->createForm(new RatingType(), $rating);
            $form->handleRequest($request);

            if ($request->isMethod('POST')) {
                if ($form->isValid()) {
                    // setting the totalScore for the rating
                    $scoreArr = array(
                        'punctualityScore' => $form->get('punctualityScore')->getData(),
                        'agreementScore'   => $form->get('agreementScore')->getData(),
                        'drivingScore'     => $form->get('drivingScore')->getData(),
                        'sociabilityScore' => $form->get('sociabilityScore')->getData(),
                        'musicScore'       => $form->get('musicScore')->getData(),
                    );
                    $totalScore = $this->get('eb_ride.ride.service')->computeAverage($scoreArr);

                    $rating->setTotalScore($totalScore);
                    $rating->setDate(new \DateTime());
                    $rating->setAwardingUser($this->getUser());
                    $rating->setReceiverUser($rideRequest->getUser());
                    $rating->setRideRequest($rideRequest);

                    // setting the rideRequest as having the rating from the user that created the Ride
                    $rideRequest->setHasDriverRating(true);

                    $em->persist($rideRequest);
                    $em->persist($rating);
                    $em->flush();

                    // dispatching the RATING_AWARDED event, which triggers the listener to send a notification to the user that will receive the rating
                    $dispatcher = $this->get('event_dispatcher');
                    $dispatcher->dispatch(NotificationEvents::RATING_AWARDED, new NotificationEvent(array(
                        'ride_request' => $rideRequest,
                        'awarding_user' => $this->getUser(),
                        'receiver_user' => $rideRequest->getUser(),
                    )));

                    return $this->redirect($this->generateUrl('rating_awarded'));
                }
            }

            return array(
                'rideRequest' => $rideRequest,
                'receiver_user' => $rideRequest->getUser(),
                'form' => $form->createView(),
            );
        } elseif ($this->getUser() == $rideRequest->getUser()) {
            // case the logged user is the one that created the rideRequest

            // check if the user that created the rideRequest has already given the rating to the user that created this ride
            $rating = $em->getRepository('EBRideBundle:Rating')->findOneBy(array(
                'awardingUser' => $this->getUser(),
                'receiverUser' => $rideRequest->getRide()->getUser(),
                'rideRequest' => $rideRequest,
            ));
            // check if the rating was already given
            if ($rating || $rideRequest->getStatus()->getId() == RideRequestStatus::REQUESTED) {
                $this->get('session')->getFlashBag()->add(
                    'warning',
                    'You can not give a rating score to this user!'
                );
                return $this->redirect($this->generateUrl('ride_show_requested_rides'));
            }

            // creating the new rating and save it to the DB
            /** @var Rating $rating */
            $rating = new Rating();

            $form = $this->createForm(new RatingType(), $rating);
            $form->handleRequest($request);

            if ($request->isMethod('POST')) {
                if ($form->isValid()) {
                    // setting the totalScore for the rating
                    $scoreArr = array(
                        'punctualityScore' => $form->get('punctualityScore')->getData(),
                        'agreementScore'   => $form->get('agreementScore')->getData(),
                        'drivingScore'     => $form->get('drivingScore')->getData(),
                        'sociabilityScore' => $form->get('sociabilityScore')->getData(),
                        'musicScore'       => $form->get('musicScore')->getData(),
                    );
                    $totalScore = $this->get('eb_ride.ride.service')->computeAverage($scoreArr);

                    $rating->setTotalScore($totalScore);
                    $rating->setDate(new \DateTime());
                    $rating->setAwardingUser($this->getUser());
                    $rating->setReceiverUser($rideRequest->getRide()->getUser());
                    $rating->setRideRequest($rideRequest);

                    // setting the rideRequest as having the rating from the user that created the rideRequest
                    $rideRequest->setHasPassengerRating(true);

                    $em->persist($rideRequest);
                    $em->persist($rating);
                    $em->flush();

                    // dispatching the RATING_AWARDED event, which triggers the listener to send a notification to the user that will receive the rating
                    $dispatcher = $this->get('event_dispatcher');
                    $dispatcher->dispatch(NotificationEvents::RATING_AWARDED, new NotificationEvent(array(
                        'ride_request' => $rideRequest,
                        'awarding_user' => $this->getUser(),
                        'receiver_user' => $rideRequest->getRide()->getUser(),
                    )));

                    return $this->redirect($this->generateUrl('rating_awarded'));
                }
            }

            return array(
                'rideRequest' => $rideRequest,
                'receiver_user' => $rideRequest->getRide()->getUser(),
                'form' => $form->createView(),
            );
        } else {
            throw $this->createNotFoundException('Not Found. Wrong URL.');
        }
    }
}
