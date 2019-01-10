<?php
/**
 * Created by PhpStorm.
 * User: connector
 * Date: 10/01/2019
 * Time: 10:58
 */

namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShiniLoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email',EmailType::class,[
              'label' => 'email',
                'attr'=>[
                    'placeholder'=>'Rentrez  votre email'
                ]
            ])
            ->add('password',PasswordType::class,[
                'label'=> 'mot de passe',
                'attr'=> [
                    'placeholder'=> 'Rentrez votre mot de passe'
                ]
            ])
            ->add('submit',SubmitType::class,[
                'label'=>'soumettre'
            ])
            ->setAction('/signIn/validate')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            ['data-class'=> null]
        );
    }

    public function getBlockPrefix()
    {
        return 'app_login';
    }

}