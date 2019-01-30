<?php
/**
 * Created by PhpStorm.
 * User: connector
 * Date: 29/01/2019
 * Time: 15:21
 */

namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class ForgottenPassordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email',EmailType::class,[

                'label'=>'email',
                'constraints'=>[
                    new NotBlank(),
                    new Email(['message' => 'Votre adresse "{{ value }}" n\'est pas valide'])
                ],


                'attr'=>[
                    'placeholder'=>'ex: email@mail.com'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data-class' => null
        ]);
    }
}