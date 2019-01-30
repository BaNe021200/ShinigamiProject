<?php
/**
 * Created by PhpStorm.
 * User: connector
 * Date: 29/01/2019
 * Time: 16:39
 */

namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;

class ResetPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                /*'constraints'=>new Regex(['pattern'=>'/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[-+!*$@%_#&])([!@#$%^&*\w]{8,20})$/','message'=>"votre mot de passe est invalide : il doit contenir au moins une majuscule, une minuscule et un de ces caractères spéciaux (!@#$%^&*)"]),*/
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => 'Mot de passe','attr'=>['placeholder'=>'Mot de passe']],
                'second_options' => ['label' => 'Confirmez le mot de passe','attr'=>['placeholder'=>'Confirmez votre mot de passe']],
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data-class' => null
        ]);
    }

}