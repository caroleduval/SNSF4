<?php

namespace App\UI\Form\Type;

use App\UI\Form\Model\ResetPassword;
use App\Domain\Repository\UserManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResetPasswordType extends AbstractType
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var UserManager
     */
    private $manager;

    public function __construct(RequestStack $requestStack, UserManager $manager)
    {
        $this->requestStack = $requestStack;
        $this->manager = $manager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('password', RepeatedType::class, [
                'required' => true,
                'type' => PasswordType::class,
                'first_options'  => array('label' => 'Nouveau mot de passe'),
                'second_options' => array('label' => 'Confirmation du nouveau mot de passe'),
            ])
            ->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                $data = $event->getData();
                if (!$data instanceof ResetPassword) {
                    throw new \RuntimeException('ResetPassword instance required.');
                }

                $token = $this->requestStack->getCurrentRequest()->get('token');

                $user = $this->manager->findUserbyToken($token);
//                dump($token,$user,$user->isResetTokenValid($token));die;

                $myresp=(! $token || ! $user || false == $user->isResetTokenValid($token)) ? false : true ;
//                dump($data,$myresp);die;

                if (false == $myresp){
                    throw new \Exception('Lien de réinitialisation non valide.');
                }
            });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => ResetPassword::class,
        ));
    }
}