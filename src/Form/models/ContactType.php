<?php
/**
 * Created by PhpStorm.
 * User: connector
 * Date: 25/01/2019
 * Time: 09:12
 */

namespace App\Form\models;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\EmailValidator;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class,[
                'constraints'=> [
                    new Length(['min'=>3,'minMessage'=>'Vous nom doit comportez au moins 3 caractères','max'=>20,'maxMessage'=>'le champs ne peut comporter plus de {{ value }} caractères']),
                    new Regex(['pattern'=>'/[a-zA-z]/']),
                    new NotBlank()
                ]
            ])
            ->add('email',EmailType::class,[
                'constraints'=> [
                    new NotBlank(),
                    new Email(['message' => 'votre email "{{ value }}" n\'est pas valide.'])
                ]
            ])
            ->add('phoneNumber',TextType::class,[
                'required'=>false,
                'constraints'=>[
                    new Regex(['pattern'=>'/[^0-9() ]+/','match' => false,'message'=>'votre numéro n\'est pas valide'])
                ],
                'attr'=>['placeholder'=>'(+33) 1 02 12 12 56']
            ])
            ->add('message',TextareaType::class,[
                'attr'=>['placeholder'=>'Merci de bien vouloir entrer ici vos doléances'],
                'constraints'=>[
                    new NotBlank(['message'=>'vous devez laissez un message']),
                    new Length(['max'=>'1000','maxMessage'=>'votre message ne peux exeder {{ value }} caractères'])
                ]
            ])
            ->add('transmettre',SubmitType::class);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null
        ]);
    }

}