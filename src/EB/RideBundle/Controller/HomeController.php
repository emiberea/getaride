<?php

namespace EB\RideBundle\Controller;

use EB\RideBundle\Entity\RideRequestStatus;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Doctrine\ORM\EntityManager;

class HomeController extends Controller
{
    /**
     * @Route("/", name="home_index")
     * @Template()
     */
    public function indexAction()
    {
        if ($this->getUser()) {
            /** @var EntityManager $em */
            $em = $this->getDoctrine()->getManager();

            $friendsNo = $em->getRepository('EBUserBundle:FriendRequest')->countAcceptedFriendRequestsByUser($this->getUser());
            $ridesNo = $this->getUser()->getRides()->count();
            $rideRequestsNo =  $em->getRepository('EBRideBundle:RideRequest')->countRideRequestsByUserAndStatus($this->getUser(), RideRequestStatus::ACCEPTED);

            return array(
                'friendsNo' => $friendsNo,
                'ridesNo' => $ridesNo,
                'rideRequestsNo' => $rideRequestsNo,
            );
        }

        return array();
    }

    /**
     * @Route("/rules", name="home_rules")
     * @Template()
     */
    public function rulesAction()
    {
        return array();
    }

    /**
     * @Route("/contact", name="home_contact")
     * @Template()
     */
    public function contactAction()
    {
        return array();
    }

    /**
     * @Route("/about", name="home_about")
     * @Template()
     */
    public function aboutAction()
    {
        return array();
    }

    /**
     * @Route("/terms", name="home_terms")
     * @Template()
     */
    public function termsAction()
    {
        return array();
    }
}
