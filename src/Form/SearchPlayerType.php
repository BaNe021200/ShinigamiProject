<?php
/**
 * Created by PhpStorm.
 * User: connector
 * Date: 18/01/2019
 * Time: 12:32
 */

namespace App\Form;


use App\Entity\ShiniPlayer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchPlayerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
       $builder
       ->add('nickName',TextType::class,[

           'attr'=>[
               'placeholder' => 'entrez votre le nom de l\'utilisateur'
           ]
       ])

    ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null
        ]);
    }

}