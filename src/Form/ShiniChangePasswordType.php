<?php

namespace App\Form;


use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShiniChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('oldpassword',TextType::class, array(
                'label' => 'Entrez le mote de passe actuel'
            ))
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => false,
                'first_options'  => ['label' => 'Mot de passe','attr'=>['placeholder'=>'Mot de passe']],
                'second_options' => ['label' => 'Confirmez le mot de passe','attr'=>['placeholder'=>'Confirmez votre mot de passe']],
            ))
            ->add('submit', SubmitType::class,[
                'label'=> "Changer le mot de passe",
                'attr'=> [
                    'class' => 'btn btn-success buttonCenter2'
                ]
            ])
        ;

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function(FormEvent $ev){
            $formData = $ev->getData();
            $form = $ev->getForm();

            // formData contient les donnée du formulaire
            /*
             * Vérifier que l'ancien password est correct !
             *
             * */
            if( $formData['password']['first'] == '') {
                $form->remove('password');
            }
            //dump($form);die;
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([

        ]);
    }
}
