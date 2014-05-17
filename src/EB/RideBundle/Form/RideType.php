<?php

namespace EB\RideBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use EB\RideBundle\Entity\RideStatus;
use EB\UserBundle\Entity\User;

class RideType extends AbstractType
{
    /** @var User $user  */
    private $user;

    /** @var RideStatus[] $rideStatuses */
    private $rideStatuses;

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->user = $options['user'];
        $this->rideStatuses = $options['rideStatuses'];

        $builder
            ->add('startDate', 'datetime', array(
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy HH:mm',
                'read_only' => true,
            ))
            ->add('startLocation')
            ->add('stopLocation')
            ->add('emptySeatsNo', 'choice', array(
                'choices' => array(
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                ),
            ))
            ->add('baggagePerSeat', 'choice', array(
                'choices' => array(
                    '0' => 'Without baggage',
                    '1' => 'Small',
                    '2' => 'Medium',
                    '3' => 'Large',
                    '4' => 'Extra-large',
                ),
            ))
            ->add('comment', 'textarea', array(
                'required' => false,
            ))
            ->add('isPublic', 'checkbox', array(
                'required' => false,
            ))
            ->add('car', 'entity', array(
                'class' => 'EBRideBundle:Car',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->where('c.user = :user')
                        ->setParameter('user', $this->user);
                },
                'empty_value' => 'Choose a car',
            ))
            ->add('rideStatus', 'entity', array(
                'class' => 'EBRideBundle:RideStatus',
                'choices' => $this->rideStatuses,
                'empty_value' => 'Select a status',
            ))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'EB\RideBundle\Entity\Ride',
            'user' => null,
            'rideStatuses' => null,
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'eb_ridebundle_ride';
    }
}
