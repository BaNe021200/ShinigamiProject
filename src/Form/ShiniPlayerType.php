<?php

namespace App\Form;

use App\Entity\ShiniPlayer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShiniPlayerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class,[
                'label' => "Prénom",
                'attr'=>[
                    'placeholder'=>"Votre prénom"
                ]
            ])
            ->add('lastname',TextType::class,[
                'label' => "Nom",
                'attr'=>[
                    'placeholder'=>"Votre Nom"
                ]
            ])
            ->add('nickName',TextType::class,[
                'label' => "Pseudo",
                'attr'=>[
                    'placeholder'=>"Votre pseudo"

                ]
            ])
            ->add('birthday',BirthdayType::class,[
                'label' => "Votre date de naissance",
                'years'=> range(1920,2010),
                'attr'=>[
                    'placeholder'=>"Votre date de naissance",
                    'format' => 'dd-mm-yyyy',

                ]
            ])
            ->add('email', EmailType::class,[
                'label' => 'Saisissez votre email',
                'attr'=> [
                    'placeholder'=> 'saisissez votre email'
                ]
            ])
            ->add('address',TextType::class,[
                'label' => "Adresse",
                'attr'=>[
                    'placeholder'=>"Votre adresse"
                ]
            ])
            ->add('city',TextType::class,[
                'label' => "Ville",
                'attr'=>[
                    'placeholder'=>"Votre ville"
                ]
            ])
            ->add('postalCode',TextType::class,[
                'label' => "Code postal",
                'attr'=>[
                    'placeholder'=>"Votre Code postal"
                ]
            ])
            ->add('phone',TextType::class,[
                'required' =>true,
                'label' => "téléphone",
                'attr'=>[
                    'placeholder'=>"Votre numéro de téléphone (10 chiffres)"
                ]
            ])
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => 'Mot de passe','attr'=>['placeholder'=>'Mot de passe']],
                'second_options' => ['label' => 'Confirmez le mot de passe','attr'=>['placeholder'=>'Confirmez votre mot de passe']],
            ))
            /*->add('cards')*/
            ->add('submit', SubmitType::class,[
                'label'=> "Soumettre",
                'attr'=> [
                    'class' => 'btn btn-success center'

                ]
            ])
            ->addEventSubscriber(new ImageSaver())
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ShiniPlayer::class,
        ]);
    }
}
