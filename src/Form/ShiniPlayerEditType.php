<?php

namespace App\Form;

use App\Entity\ShiniPlayer;
use App\ImageSaver\ImageSaver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShiniPlayerEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class,[
                'required' =>true,
                'label' => "Prénom",
                'attr'=>[
                    'placeholder'=>"Votre prénom"
                ]
            ])
            ->add('lastname',TextType::class,[
                'required' =>true,
                'label' => "Nom",
                'attr'=>[
                    'placeholder'=>"Votre Nom"
                ]
            ])
            ->add('nickName',TextType::class,[
                'required' =>true,
                'label' => "Pseudo",
                'attr'=>[
                    'placeholder'=>"Votre pseudo"

                ]
            ])
            ->add('birthday',BirthdayType::class,[
                'required' =>true,
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
                'required' =>true,
                'label' => "Adresse",
                'attr'=>[
                    'placeholder'=>"Votre adresse"
                ]
            ])
            ->add('city',TextType::class,[
                'required' =>true,
                'label' => "Ville",
                'attr'=>[
                    'placeholder'=>"Votre ville"
                ]
            ])
            ->add('postalCode',TextType::class,[
                'required' =>true,
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
                'required' => false,
                'first_options'  => ['label' => 'Mot de passe','attr'=>['placeholder'=>'Mot de passe']],
                'second_options' => ['label' => 'Confirmez le mot de passe','attr'=>['placeholder'=>'Confirmez votre mot de passe']],
            ))
            ->add('submit', SubmitType::class,[
                'label'=> "Update",
                'attr'=> [
                    'class' => 'btn btn-success buttonCenter2'

                ]
            ])
            ->addEventSubscriber(new ImageSaver())
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ShiniPlayer::class,
            'allow_extra_fields' =>true
        ]);
    }
}
