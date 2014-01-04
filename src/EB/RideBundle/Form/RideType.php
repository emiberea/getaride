<?php

namespace EB\RideBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class RideType extends AbstractType
{
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
            ->add('car', 'entity', array(
                'class' => 'EBRideBundle:Car',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('r')
                        ->where('r.user = :user')
                        ->setParameter('user', $this->user);
                },
                'empty_value' => 'Choose an option',
                'required' => false,
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
