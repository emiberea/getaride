<?php

namespace EB\RideBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use EB\CommunicationBundle\Event\NotificationEvent;
use EB\CommunicationBundle\Event\NotificationEvents;
use EB\RideBundle\Entity\Ride;
use EB\RideBundle\Entity\RideStatus;
use EB\RideBundle\Form\RideType;
use EB\RideBundle\Form\RideSearchType;

/**
 * Ride controller.
 *
 * @Route("/ride")
 */
class RideController extends Controller
{

    /**
     * Lists all Ride entities.
     *
     * @Route("/", name="ride")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $userId = $this->getUser()->getId();

        $dql = "SELECT r FROM EBRideBundle:Ride r
                WHERE r.user = '$userId'";
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
     * Creates a new Ride entity.
     *
     * @Route("/", name="ride_create")
     * @Method("POST")
     * @Template("EBRideBundle:Ride:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $ride = new Ride();

        $form = $this->createCreateForm($ride);
        $form->handleRequest($request);

        if ($form->isValid()) {
            /** @var EntityManager $em */
            $em = $this->getDoctrine()->getManager();

            $ride->setUser($this->getUser());
            if ($ride->getRideStatus()->getId() == RideStatus::AVAILABLE && $ride->getWasAvailable() == false) {
                $ride->setWasAvailable(true);
            }

            $em->persist($ride);
            $em->flush();

            return $this->redirect($this->generateUrl('ride_show', array('id' => $ride->getId())));
        }

        return array(
            'ride' => $ride,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Ride entity.
     *
     * @param Ride $ride The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Ride $ride)
    {
        $form = $this->createForm(new RideType(), $ride, array(
            'action' => $this->generateUrl('ride_create'),
            'method' => 'POST',
            'user' => $this->getUser(),
            'rideStatuses' => array(
                $this->getDoctrine()->getManager()->getRepository('EBRideBundle:RideStatus')->find(RideStatus::DRAFT),
                $this->getDoctrine()->getManager()->getRepository('EBRideBundle:RideStatus')->find(RideStatus::AVAILABLE),
            ),
        ));

        $form->add('submit', 'submit', array(
            'label' => 'Create',
            'attr' => array(
                'class' => 'btn btn-success'
            ),
        ));

        return $form;
    }

    /**
     * Displays a form to create a new Ride entity.
     *
     * @Route("/new", name="ride_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $ride = new Ride();
        $form   = $this->createCreateForm($ride);

        return array(
            'ride' => $ride,
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/search", name="ride_search")
     * @Template()
     */
    public function searchAction()
    {
        $form = $this->createForm(new RideSearchType());

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/search-results", name="ride_search_results")
     * @Method("POST")
     * @Template("EBRideBundle:Ride:search.html.twig")
     */
    public function getSearchResultAction(Request $request)
    {
        $form = $this->createForm(new RideSearchType());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $matchExactly = $form->get('matchExactly')->getData();
            $searchParams = array(
                'startDate' => $form->get('startDate')->getData(),
                'startLocation' => $form->get('startLocation')->getData(),
                'stopLocation' => $form->get('stopLocation')->getData(),
                'emptySeatsNo' => $form->get('emptySeatsNo')->getData(),
                'baggagePerSeat' => $form->get('baggagePerSeat')->getData(),
            );

            /** @var EntityManager $em */
            $em = $this->getDoctrine()->getManager();

            if ($matchExactly) {
                $rides = $em->getRepository('EBRideBundle:Ride')
                    ->getRidesByDateLocationAndSeatsNo($searchParams, $this->getUser());
            } else {
                $rides = $em->getRepository('EBRideBundle:Ride')
                    ->getRidesByDateLocationOrSeatsNo($searchParams, $this->getUser());
            }

            return array(
                'form' => $form->createView(),
                'rides' => $rides,
            );
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/requested", name="ride_show_requested_rides")
     * @Template()
     */
    public function showRequestedRidesAction()
    {
        $em = $this->getDoctrine()->getManager();

        $userId = $this->getUser()->getId();

        $dql = "SELECT rr FROM EBRideBundle:RideRequest rr
                WHERE rr.user = '$userId'
                ORDER BY rr.id DESC";
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
     * Finds and displays a Ride entity.
     *
     * @Route("/{id}", name="ride_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        /** @var Ride $ride */
        $ride = $em->getRepository('EBRideBundle:Ride')->findOneBy(array(
            'id' => $id,
            'user' => $this->getUser(),
        ));

        if (!$ride) {
            throw $this->createNotFoundException('Unable to find Ride entity.');
        }

        $waypointsArr = json_decode($ride->getWaypointsStr(), true);

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'ride' => $ride,
            'waypointsArr' => $waypointsArr,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * @Route("/{id}/public", name="ride_show_public")
     * @Method("GET")
     * @Template()
     */
    public function showPublicAction($id)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        /** @var Ride $ride */
        $ride = $em->getRepository('EBRideBundle:Ride')->findOneBy(array(
            'id' => $id,
        ));

        // check if the ride exists and if the user that it is viewing it has rights for that
        if (!$ride) {
            throw $this->createNotFoundException('Unable to find Ride entity.');
        }
        if ($ride->getIsPublic() == false || $ride->getRideStatus()->getId() == RideStatus::DRAFT) {
            throw $this->createNotFoundException('Unable to find Ride entity.');
        }

        // get the rideRequest that belong to this ride and this attempting user
        // added a security check in case there are more rideRequest for the same user, even if it is also checked at rideRequest creation
        $rideRequests = $em->getRepository('EBRideBundle:RideRequest')->findByRideAndUser($ride, $this->getUser());
        $rideRequest = end($rideRequests) ? end($rideRequests) : null;

        $waypointsArr = json_decode($ride->getWaypointsStr(), true);

        return array(
            'ride' => $ride,
            'rideRequest' => $rideRequest,
            'waypointsArr' => $waypointsArr,
        );
    }

    /**
     * @Route("/{id}/requesting-users", name="ride_show_requesting_users")
     * @Template()
     */
    public function showRequestingUsersAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $ride = $em->getRepository('EBRideBundle:Ride')->findOneBy(array(
            'id' => $id,
            'user' => $this->getUser(),
        ));

        if (!$ride) {
            throw $this->createNotFoundException('Unable to find Ride entity.');
        }

        return array(
            'ride' => $ride,
        );
    }

    /**
     * Displays a form to edit an existing Ride entity.
     *
     * @Route("/{id}/edit", name="ride_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        /** @var Ride $ride */
        $ride = $em->getRepository('EBRideBundle:Ride')->findOneBy(array(
            'id' => $id,
            'user' => $this->getUser(),
        ));

        if (!$ride) {
            throw $this->createNotFoundException('Unable to find Ride entity.');
        }

        $waypointsArr = json_decode($ride->getWaypointsStr(), true);

        $editForm = $this->createEditForm($ride);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'ride' => $ride,
            'waypointsArr' => $waypointsArr,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Creates a form to edit a Ride entity.
     *
     * @param Ride $ride The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Ride $ride)
    {
        if ($ride->getRideStatus()->getId() == RideStatus::DRAFT) {
            $rideStatuses = array(
                $this->getDoctrine()->getManager()->getRepository('EBRideBundle:RideStatus')->find(RideStatus::DRAFT),
                $this->getDoctrine()->getManager()->getRepository('EBRideBundle:RideStatus')->find(RideStatus::AVAILABLE),
            );
        } elseif ($ride->getRideStatus()->getId() == RideStatus::AVAILABLE) {
            $rideStatuses = array(
                $this->getDoctrine()->getManager()->getRepository('EBRideBundle:RideStatus')->find(RideStatus::AVAILABLE),
                $this->getDoctrine()->getManager()->getRepository('EBRideBundle:RideStatus')->find(RideStatus::CANCELED),
                $this->getDoctrine()->getManager()->getRepository('EBRideBundle:RideStatus')->find(RideStatus::CLOSED),
            );
        } elseif ($ride->getRideStatus()->getId() == RideStatus::CANCELED) {
            $rideStatuses = array(
                $this->getDoctrine()->getManager()->getRepository('EBRideBundle:RideStatus')->find(RideStatus::CANCELED),
            );
        } elseif ($ride->getRideStatus()->getId() == RideStatus::CLOSED || $ride->getRideStatus()->getId() == RideStatus::FINISH_FAIL || $ride->getRideStatus()->getId() == RideStatus::FINISH_SUCCESS) {
            $rideStatuses = array(
                $this->getDoctrine()->getManager()->getRepository('EBRideBundle:RideStatus')->find(RideStatus::CLOSED),
                $this->getDoctrine()->getManager()->getRepository('EBRideBundle:RideStatus')->find(RideStatus::FINISH_FAIL),
                $this->getDoctrine()->getManager()->getRepository('EBRideBundle:RideStatus')->find(RideStatus::FINISH_SUCCESS),
            );
        } else {
            $rideStatuses = $this->getDoctrine()->getManager()->getRepository('EBRideBundle:RideStatus')->findAll();
        }

        $form = $this->createForm(new RideType(), $ride, array(
            'action' => $this->generateUrl('ride_update', array('id' => $ride->getId())),
            'method' => 'PUT',
            'user' => $this->getUser(),
            'rideStatuses' => $rideStatuses,
        ));

        $form->add('submit', 'submit', array(
            'label' => 'Update',
            'attr' => array(
                'class' => 'btn btn-warning'
            ),
        ));

        return $form;
    }

    /**
     * Edits an existing Ride entity.
     *
     * @Route("/{id}", name="ride_update")
     * @Method("PUT")
     * @Template("EBRideBundle:Ride:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        /** @var Ride $ride */
        $ride = $em->getRepository('EBRideBundle:Ride')->findOneBy(array(
            'id' => $id,
            'user' => $this->getUser(),
        ));

        if (!$ride) {
            throw $this->createNotFoundException('Unable to find Ride entity.');
        }

        $waypointsArr = json_decode($ride->getWaypointsStr(), true);

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($ride);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
             if ($ride->getRideStatus()->getId() == RideStatus::AVAILABLE && $ride->getWasAvailable() == false) {
                 $ride->setWasAvailable(true);
                 $em->flush();

                 return $this->redirect($this->generateUrl('ride_edit', array('id' => $id)));
             } elseif ($ride->getRideStatus()->getId() == RideStatus::CANCELED && $ride->getWasCanceled() == false) {
                // setting the isCanceled flag to true, to prevent sending notifications multiple times, if the user reedits the status to CANCELED
                $ride->setWasCanceled(true);
                $em->flush();

                // dispatching the RIDE_CANCELED event, which triggers the listener to send a notification to all the requesting users that created rideRequests for this ride
                $dispatcher = $this->get('event_dispatcher');
                $dispatcher->dispatch(NotificationEvents::RIDE_CANCELED, new NotificationEvent(array(
                    'ride' => $ride,
                )));

                return $this->redirect($this->generateUrl('ride_edit', array('id' => $id)));
            } elseif ($ride->getRideStatus()->getId() == RideStatus::CLOSED && $ride->getWasClosed() == false) {
                if ($ride->getStartDate() > new \DateTime()) {
                    $this->get('session')->getFlashBag()->add(
                        'error',
                        sprintf('You can not mark the ride as CLOSED if it is scheduled to take place in the future, at %s. You can just change the start date.',
                            $ride->getStartDate()->format('d-m-Y H:i')
                        )
                    );
                    return $this->redirect($this->generateUrl('ride_edit', array('id' => $id)));
                }

                // setting the isClosed flag to true, to prevent sending notifications multiple times, if the user reedits the status to CLOSED
                $ride->setWasClosed(true);
                $em->flush();

                // dispatching the RIDE_CLOSED event, which triggers the listener to send a notification to the user that created the ride
                // and the users that have the rideRequest accepted and invite them to give a rating to each other
                $dispatcher = $this->get('event_dispatcher');
                $dispatcher->dispatch(NotificationEvents::RIDE_CLOSED, new NotificationEvent(array(
                    'ride' => $ride,
                )));

                return $this->redirect($this->generateUrl('ride_edit', array('id' => $id)));
            } elseif (($ride->getRideStatus()->getId() == RideStatus::FINISH_FAIL || $ride->getRideStatus()->getId() == RideStatus::FINISH_SUCCESS)
                && $ride->getWasFinished() == false) {

                $ride->setWasFinished(true);
                $em->flush();

                return $this->redirect($this->generateUrl('ride_edit', array('id' => $id)));
            } elseif ($ride->getRideStatus()->getId() == RideStatus::DRAFT) {
                 $em->flush();
                 return $this->redirect($this->generateUrl('ride_edit', array('id' => $id)));
            } else {
                $this->get('session')->getFlashBag()->add(
                    'error',
                    sprintf('You can not edit the ride details and mark it with %s status!',
                        $ride->getRideStatus()->getName()
                    )
                );
                return $this->redirect($this->generateUrl('ride_edit', array('id' => $id)));
            }
        }

        return array(
            'ride'         => $ride,
            'waypointsArr' => $waypointsArr,
            'edit_form'    => $editForm->createView(),
            'delete_form'  => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Ride entity.
     *
     * @Route("/{id}", name="ride_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $ride = $em->getRepository('EBRideBundle:Ride')->findOneBy(array(
                'id' => $id,
                'user' => $this->getUser(),
            ));

            if (!$ride) {
                throw $this->createNotFoundException('Unable to find Ride entity.');
            }

            $em->remove($ride);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('ride'));
    }

    /**
     * Creates a form to delete a Ride entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('ride_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array(
                'label' => 'Delete',
                'attr' => array(
                    'class' => 'btn btn-danger'
                ),
            ))
            ->getForm()
        ;
    }
}
