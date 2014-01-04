<?php

namespace EB\RideBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RideType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startDate')
            ->add('stopDate')
            ->add('startLocation')
            ->add('stopLocation')
            ->add('emptySeatsNo')
            ->add('baggagePerSeat')
            ->add('comment')
            ->add('car')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'EB\RideBundle\Entity\Ride'
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
