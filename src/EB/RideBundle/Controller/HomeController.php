<?php

namespace EB\RideBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class HomeController extends Controller
{
    /**
     * @Route("/", name="home_index")
     * @Template()
     */
    public function indexAction()
    {
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
