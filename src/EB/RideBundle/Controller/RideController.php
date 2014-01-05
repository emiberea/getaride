<?php

namespace EB\RideBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use EB\RideBundle\Entity\Ride;
use EB\RideBundle\Form\RideType;

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
        $em = $this->getDoctrine()->getManager();

        $rides = $em->getRepository('EBRideBundle:Ride')->findBy(array(
            'user' => $this->getUser(),
        ));

        return array(
            'rides' => $rides,
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
        $ride->setUser($this->getUser());

        $form = $this->createCreateForm($ride);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
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
     * Finds and displays a Ride entity.
     *
     * @Route("/{id}", name="ride_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $ride = $em->getRepository('EBRideBundle:Ride')->findOneBy(array(
            'id' => $id,
            'user' => $this->getUser(),
        ));

        if (!$ride) {
            throw $this->createNotFoundException('Unable to find Ride entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'ride'        => $ride,
            'delete_form' => $deleteForm->createView(),
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
        $em = $this->getDoctrine()->getManager();

        $ride = $em->getRepository('EBRideBundle:Ride')->findOneBy(array(
            'id' => $id,
            'user' => $this->getUser(),
        ));

        if (!$ride) {
            throw $this->createNotFoundException('Unable to find Ride entity.');
        }

        $editForm = $this->createEditForm($ride);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'ride'        => $ride,
            'edit_form'   => $editForm->createView(),
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
        $form = $this->createForm(new RideType(), $ride, array(
            'action' => $this->generateUrl('ride_update', array('id' => $ride->getId())),
            'method' => 'PUT',
            'user' => $this->getUser(),
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
        $em = $this->getDoctrine()->getManager();

        $ride = $em->getRepository('EBRideBundle:Ride')->findOneBy(array(
            'id' => $id,
            'user' => $this->getUser(),
        ));

        if (!$ride) {
            throw $this->createNotFoundException('Unable to find Ride entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($ride);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('ride_edit', array('id' => $id)));
        }

        return array(
            'ride'        => $ride,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
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
