<?php

namespace App\UI\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->remove('username')
            ->add('firstName', TextType::class, array(
                'label' => 'form.firstName',
                'translation_domain' => 'FOSUserBundle'
            ))
            ->add('name', TextType::class, array(
                'label' => 'form.name',
                'translation_domain' => 'FOSUserBundle'
            ))
            ->add('photo', PhotoType::class, array(
                'required' => false,
            ))
            ->add('Je m\'enregistre',      SubmitType::class)
        ;
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }

    public function getBlockPrefix()
    {
        return 'app_user_registration';
    }
}
