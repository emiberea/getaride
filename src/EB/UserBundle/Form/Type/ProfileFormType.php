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
            ))
            ->add('work')
            ->add('isSmoker')
            ->add('favoriteMusic')
            ->add('hobbies')
            ->add('personalDescription', 'textarea', array())
            ->add('isDriver')
            ->add('drivingLicenceDate', 'date', array(
                'widget' => 'single_text',
            ));
    }

    public function getName()
    {
        return 'eb_user_profile';
    }
}
