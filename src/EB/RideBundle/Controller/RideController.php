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

        $entities = $em->getRepository('EBRideBundle:Ride')->findBy(array(
            'user' => $this->getUser(),
        ));

        return array(
            'entities' => $entities,
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
        $entity = new Ride();
        $entity->setUser($this->getUser());

        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('ride_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a Ride entity.
    *
    * @param Ride $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Ride $entity)
    {
        $form = $this->createForm(new RideType(), $entity, array(
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
        $entity = new Ride();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
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

        $entity = $em->getRepository('EBRideBundle:Ride')->findOneBy(array(
            'id' => $id,
            'user' => $this->getUser(),
        ));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Ride entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
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

        $entity = $em->getRepository('EBRideBundle:Ride')->findOneBy(array(
            'id' => $id,
            'user' => $this->getUser(),
        ));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Ride entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Ride entity.
    *
    * @param Ride $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Ride $entity)
    {
        $form = $this->createForm(new RideType(), $entity, array(
            'action' => $this->generateUrl('ride_update', array('id' => $entity->getId())),
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

        $entity = $em->getRepository('EBRideBundle:Ride')->findOneBy(array(
            'id' => $id,
            'user' => $this->getUser(),
        ));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Ride entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('ride_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
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
            $entity = $em->getRepository('EBRideBundle:Ride')->findOneBy(array(
                'id' => $id,
                'user' => $this->getUser(),
            ));

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Ride entity.');
            }

            $em->remove($entity);
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
