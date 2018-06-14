<?php

namespace App\UI\Form\Type;

use App\UI\Form\Model\Identifier;
use App\Domain\Repository\UserManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class ResetRequestType extends AbstractType
{
    /**
     * @var UserManager
     */
    private $manager;

    /**
     * ResetRequestType constructor.
     * @param UserManager $manager
     */
    public function __construct(UserManager $manager)
    {
        $this->manager=$manager;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'required' => true,
            ])
            ->addEventListener(
                FormEvents::POST_SUBMIT,
                function (FormEvent $event) {
                    $identifier = $event->getData();

                    if (!$identifier instanceof Identifier) {
                        throw new \RuntimeException('Identifier instance required.');
                    }
                    $email = $identifier->getEmail();
                    $form = $event->getForm();

                    if (!$email || count($form->getErrors(true))) {
                        return;
                    }

                    $user = $this->manager->findUserbyEmail($email);

                    if (null == $user) {
                        $form->get('email')->addError(new FormError('Utilisateur inconnu'));
                        return;
                    } else {
                        $identifier->setUser($user);
                    };
                })
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Identifier::class,
        ));
    }
}