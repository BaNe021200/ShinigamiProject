<?php
/**
 * Created by PhpStorm.
 * User: connector
 * Date: 08/01/2019
 * Time: 10:08
 */

namespace App\Form;


use App\Entity\ShiniCenter;
use App\Entity\ShiniStaff;
use App\ImageSaver\ImageSaver;
use Symfony\Bridge\Doctrine\Form\DataTransformer\CollectionToArrayTransformer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShiniStaffType extends ShiniPlayerType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder,$options);
        $builder
            ->add('roles',ChoiceType::class,[

                'choices'=>[
                    'admin' =>'ROLE_ADMIN',
                    'staff'=>'ROLE_STAFF',
                    ],
                'multiple' => true,
                'expanded' =>true,

            ])

            ->add('center', EntityType::class, [
                'class' => ShiniCenter::class,
                'choice_label' => 'code',
            ])
            ->addEventSubscriber(new ImageSaver())

        ;

        $builder->get('roles')->addModelTransformer(new CollectionToArrayTransformer());
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefault('data_class',ShiniStaff::class);
    }
}