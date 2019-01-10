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

class ShiniImageType extends AbstractType
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
            ->add('submit', SubmitType::class,[
                'label'=> "Je m'inscris",
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
