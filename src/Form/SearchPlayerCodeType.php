<?php
/**
 * Created by PhpStorm.
 * User: connector
 * Date: 18/01/2019
 * Time: 15:51
 */

namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchPlayerCodeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('cardCode', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'entrer le code '
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null
        ]);
    }

}