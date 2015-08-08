<?php

namespace EB\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Doctrine\ORM\EntityManager;
use FOS\RestBundle\View\View;

class UserController extends Controller
{
    /**
     * @Route("/users", name="eb_api_user_getAll")
     * @Method("GET")
     */
    public function getUsersAction()
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('EBUserBundle:User')->findAll();
        $data = array('data' => $users);

        $view = View::create();
        $view->setData($data);
        $view->setFormat('json');
        $view->setStatusCode(200);

        return $view;
    }

    /**
     * @Route("/user/{username}", name="eb_api_user_get")
     * @Method("GET")
     */
    public function getUserAction($username)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('EBUserBundle:User')->findOneBy(array(
            'username' => $username,
        ));
        if (!$user) {
            throw $this->createNotFoundException('User with username ' . $username . ' not found.');
        }

        $links = array(
            0 => array(
                'rel' => 'self',
                'href' => $this->get('router')->generate('eb_api_user_get', array('username' => $username)),
            ),
            1 => array(
                'rel' => '/linkrels/users',
                'href' => $this->get('router')->generate('eb_api_user_getAll'),
            ),
        );
        $data = array(
            'data' => $user,
            'links' => $links,
        );

        $view = View::create();
        $view->setData($data);
        $view->setFormat('json');
        $view->setStatusCode(200);

        return $view;
    }
}
