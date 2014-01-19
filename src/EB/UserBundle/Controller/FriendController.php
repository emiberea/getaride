<?php

namespace EB\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use EB\UserBundle\Entity\User;
use EB\UserBundle\Entity\FriendRequest;
use EB\UserBundle\Entity\FriendRequestStatus;
use EB\UserBundle\Form\UserSearchType;

/**
 * Friend controller.
 *
 * @Route("/friend")
 */
class FriendController extends Controller
{
    /**
     * @Route("/", name="friend_index")
     * @Template()
     */
    public function indexAction()
    {
        /** @var User $user */
        $user = $this->getUser();

        /** @var User[] $friends */
        $friends = array();

        /** @var FriendRequest[] $friendRequests */
        $friendRequests = $user->getFriendRequests();
        foreach ($friendRequests as $friendRequest) {
            if ($friendRequest->getStatus()->getId() == FriendRequestStatus::ACCEPTED) {
                $friends[] = $friendRequest->getReceiver();
            }
        }

        /** @var FriendRequest[] $friendResponses */
        $friendResponses = $user->getFriendResponses();
        foreach ($friendResponses as $friendResponse) {
            if ($friendResponse->getStatus()->getId() == FriendRequestStatus::ACCEPTED) {
                $friends[] = $friendResponse->getSender();
            }
        }

        return array(
            'friends' => $friends,
        );
    }

    /**
     * @Route("/search", name="friend_search")
     * @Template()
     */
    public function searchAction()
    {
        $form = $this->createForm(new UserSearchType());

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/search-results", name="friend_search_results")
     * @Method("POST")
     * @Template("EBUserBundle:Friend:search.html.twig")
     */
    public function getSearchResultsAction(Request $request)
    {
        $form = $this->createForm(new UserSearchType());
        $form->handleRequest($request);

        $searchTerm = $form->get('user')->getData();

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $users = $em->getRepository('EBUserBundle:User')->findUsersByUsername($this->getUser(), $searchTerm);

            return array(
                'form' => $form->createView(),
                'users' => $users,
            );
        }

        return array(
            'form' => $form->createView(),
        );
    }
}
