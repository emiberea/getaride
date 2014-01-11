<?php

namespace EB\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\UserBundle\Controller\ProfileController as BaseController;

/**
 * ProfileController
 */
class ProfileController extends BaseController
{
    /**
     * @Route("/user/{username}", name="eb_user_public_profile")
     * @Template()
     */
    public function showPublicAction($username)
    {
        $em = $this->container->get('doctrine');
        $user = $em->getRepository('EBUserBundle:User')->findOneBy(array(
            'username' => $username,
        ));

        if (!$user) {
            throw new NotFoundHttpException('Unable to find User entity.');
        }

        return array(
            'user' => $user,
        );
    }
}
