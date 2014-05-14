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
                'format' => 'dd-MM-yyyy',
                'label' => 'Start Date',
                'required'  => false,
                'read_only' => true,
            ))
            ->add('startLocation', null, array(
                'label' => 'Start Location',
                'required'  => false,
            ))
            ->add('stopLocation', null, array(
                'label' => 'Stop Location',
                'required'  => false,
            ))
            ->add('emptySeatsNo', 'choice', array(
                'choices' => array(
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                ),
                'label' => 'Empty Seats No',
                'required'  => false,
                'empty_value' => 'Any number available',
            ))
            ->add('baggagePerSeat', 'choice', array(
                'choices' => array(
                    '0' => 'Without baggage',
                    '1' => 'Small',
                    '2' => 'Medium',
                    '3' => 'Large',
                    '4' => 'Extra-large',
                ),
                'label' => 'Baggage Per Seat',
                'required'  => false,
                'empty_value' => 'All types of baggage',
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
