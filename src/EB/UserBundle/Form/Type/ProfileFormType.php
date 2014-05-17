<?php

namespace EB\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\ProfileFormType as BaseType;

class ProfileFormType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        // custom fields
        $builder
            ->add('firstname')
            ->add('lastname')
            ->add('city')
            ->add('address')
            ->add('phone')
            ->add('birthDate', 'birthday', array(
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
                'read_only' => true,
            ))
            ->add('gender', 'choice', array(
                'choices' => array(
                    'male' => 'male',
                    'female' => 'female',
                ),
                'required' => false,
                'empty_value' => 'Select gender',
            ))
            ->add('work')
            ->add('isSmoker')
            ->add('favoriteMusic')
            ->add('hobbies')
            ->add('personalDescription', 'textarea', array(
                'required' => false,
            ))
            ->add('isDriver')
            ->add('drivingLicenceDate', 'date', array(
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
                'read_only' => true,
            ));
    }

    public function getName()
    {
        return 'eb_user_profile';
    }
}
