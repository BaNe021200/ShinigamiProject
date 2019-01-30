<?php
/**
 * Created by PhpStorm.
 * User: connector
 * Date: 06/01/2019
 * Time: 17:21
 */

namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\ShiniPlayer;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class ShiniSignInType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class,[
                'required' =>true,
                'label' => "Prénom",
                'attr'=>[
                    'placeholder'=>"Votre prénom",
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
            ->add('email', EmailType::class,[
                'label' => 'Saisissez votre email',
                'attr'=> [
                    'placeholder'=> 'saisissez votre email'
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
            /*->add('submit', SubmitType::class,[
                'label'=> "Je m'inscris",
                'attr'=> [
                    'class' => 'btn btn-success center'

                ]
            ])*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ShiniPlayer::class,
        ]);
    }

}