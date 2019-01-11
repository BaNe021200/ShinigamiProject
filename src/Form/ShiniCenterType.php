<?php
/**
 * Created by PhpStorm.
 * User: connector
 * Date: 07/01/2019
 * Time: 16:06
 */

namespace App\Form;


use App\Entity\ShiniCenter;
use App\ImageSaver\ImageSaver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShiniCenterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code')
            ->add('submit', SubmitType::class,[
                'label' => 'Soumettre',
                ])
            ->addEventSubscriber(new ImageSaver())
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
           'data_class' => ShiniCenter::class

        ]);
    }

}