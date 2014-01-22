<?php

namespace EB\RideBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RideSearchType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startDate', 'date', array(
                'widget' => 'single_text',
                'label' => 'Start Date',
                'required'  => false,
            ))
            ->add('startLocation', null, array(
                'label' => 'Start Location',
                'required'  => false,
            ))
            ->add('stopLocation', null, array(
                'label' => 'Stop Location',
                'required'  => false,
            ))
            ->add('emptySeatsNo', null, array(
                'label' => 'Empty Seats No',
                'required'  => false,
            ))
            ->add('baggagePerSeat', null, array(
                'label' => 'Baggage Per Seat',
                'required'  => false,
            ))
            ->add('matchExactly', 'checkbox', array(
                'label' => 'Match Exactly',
                'required'  => false,
            ))
            ->add('submit', 'submit', array(
                'label' => 'Search',
                'attr' => array(
                    'class' => 'btn btn-default'
                ),
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => null
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'eb_ridebundle_ridesearch';
    }
}
