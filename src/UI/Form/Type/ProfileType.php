<?php

namespace App\UI\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->remove('current_password')
            ->remove('username')
            ->add('name', TextType::class)
            ->add('firstName', TextType::class)
            ->add('photo', PhotoType::class, array(
                'required' => false,
            ))
        ;
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\ProfileFormType';
    }

    public function getBlockPrefix()
    {
        return 'fos_user_profile_edit';
    }
}
