<?php

namespace EB\RideBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use EB\RideBundle\Entity\Car;
use EB\RideBundle\Form\CarType;

/**
 * Car controller.
 *
 * @Route("/car")
 */
class CarController extends Controller
{

    /**
     * Lists all Car entities.
     *
     * @Route("/", name="car")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('EBRideBundle:Car')->findBy(array(
            'user' => $this->getUser(),
        ));

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new Car entity.
     *
     * @Route("/", name="car_create")
     * @Method("POST")
     * @Template("EBRideBundle:Car:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Car();
        $entity->setUser($this->getUser());

        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('car_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a Car entity.
    *
    * @param Car $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Car $entity)
    {
        $form = $this->createForm(new CarType(), $entity, array(
            'action' => $this->generateUrl('car_create'),
            'method' => 'POST',
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
     * Displays a form to create a new Car entity.
     *
     * @Route("/new", name="car_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Car();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Car entity.
     *
     * @Route("/{id}", name="car_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('EBRideBundle:Car')->findOneBy(array(
            'id' => $id,
            'user' => $this->getUser(),
        ));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Car entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Car entity.
     *
     * @Route("/{id}/edit", name="car_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('EBRideBundle:Car')->findOneBy(array(
            'id' => $id,
            'user' => $this->getUser(),
        ));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Car entity.');
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
    * Creates a form to edit a Car entity.
    *
    * @param Car $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Car $entity)
    {
        $form = $this->createForm(new CarType(), $entity, array(
            'action' => $this->generateUrl('car_update', array('id' => $entity->getId())),
            'method' => 'PUT',
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
     * Edits an existing Car entity.
     *
     * @Route("/{id}", name="car_update")
     * @Method("PUT")
     * @Template("EBRideBundle:Car:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('EBRideBundle:Car')->findOneBy(array(
            'id' => $id,
            'user' => $this->getUser(),
        ));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Car entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('car_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Car entity.
     *
     * @Route("/{id}", name="car_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('EBRideBundle:Car')->findOneBy(array(
                'id' => $id,
                'user' => $this->getUser(),
            ));

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Car entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('car'));
    }

    /**
     * Creates a form to delete a Car entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('car_delete', array('id' => $id)))
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
