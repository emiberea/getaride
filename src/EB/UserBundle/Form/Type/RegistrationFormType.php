<?php

namespace EB\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;
use Symfony\Component\Validator\Constraints\True;

class RegistrationFormType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        // custom fields
        $builder
            ->add('firstname')
            ->add('lastname')
            ->add('terms', 'checkbox', array(
                'mapped' => false,
                'label' => 'Terms of Service',
                'constraints' => array(
                    new True(array('message' => 'In order to use our services, you must agree to our Terms of Service.'))
                ),
            ));
    }

    public function getName()
    {
        return 'eb_user_registration';
    }
}
