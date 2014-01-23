<?php

namespace EB\RideBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use EB\UserBundle\Entity\User;

class RideType extends AbstractType
{
    /** @var User $user  */
    private $user;

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->user = $options['user'];

        $builder
            ->add('startDate', 'date', array(
                'widget' => 'single_text',
            ))
            ->add('stopDate', 'date', array(
                'widget' => 'single_text',
            ))
            ->add('startLocation')
            ->add('stopLocation')
            ->add('emptySeatsNo')
            ->add('baggagePerSeat')
            ->add('comment', 'textarea', array())
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
