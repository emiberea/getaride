<?php

namespace EB\RideBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;

/**
 * Rating controller.
 *
 * @Route("/rating")
 */
class RatingController extends Controller
{
    /**
     * @Route("/awarded", name="rating_awarded")
     * @Template()
     */
    public function awardedAction()
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $userId = $this->getUser()->getId();

        $dql = "SELECT r FROM EBRideBundle:Rating r
                WHERE r.awardingUser = '$userId'
                ORDER BY r.id DESC";
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
     * @Route("/received", name="rating_received")
     * @Template()
     */
    public function receivedAction()
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $userId = $this->getUser()->getId();

        $dql = "SELECT r FROM EBRideBundle:Rating r
                WHERE r.receiverUser = '$userId'
                ORDER BY r.id DESC";
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
