<?php

namespace App\UI\Form\Type;

use App\Domain\Model\Trick;
//use App\Event\Subscriber\ManageTrickSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormInterface;

class TrickType extends AbstractType
{
//    private $listener;
//
//    public function __construct(ManageTrickSubscriber $listener)
//    {
//        $this->listener=$listener;
//    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class)
            ->add('description',TextareaType::class)
            ->add('category',EntityType::class, array(
                'class' => 'App:Category',
                'choice_label' => 'name',
                'multiple' => false,
                'expanded' => false,
            ))
            ->add('photos', CollectionType::class, array(
                'required' => false,
                'entry_type' => PhotoType::class,
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label'        => false,
            ))
            ->add('videos', CollectionType::class, array(
                'required' => false,
                'entry_type' => VideoType::class,
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label'        => false,
            ))
//            ->addEventSubscriber($this->listener)
            ->getForm()
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id'   => 'trick_item'
                ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_trick';
    }
}
