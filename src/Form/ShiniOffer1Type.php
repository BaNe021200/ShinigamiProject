<?php

namespace App\Form;

use App\Entity\ShiniOffer;
use App\Entity\ShiniStaff;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShiniOffer1Type extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('price')
            ->add('dateEnd',DateType::class)
            ->add('description',CKEditorType::class,[
                'label'=>'Description',
                'attr'=> [
                    'placeholder'=>"Description de l'offre"
                ],'config' =>[
                    'toolbar'=>'standard'
                ]
            ])
            ->add('image')
            ->add('shown',CheckboxType::class,[
                'label'=> "visible en ligne",
                'required' => false,
                'attr'=>[
                    'data-toggle'=>"toggle",
                    'data-on'=> 'oui',
                    'data-off'=>'non',
                    'data-onstyle'=>"success",
                    'data-offstyle'=>"danger",
                    'novalidate' => 'novalidate'

                ]
            ])
            ->add('onfirstpage',CheckboxType::class,[
                'label'=>"a la une",
                'required' => false,
                'attr'=>[
                    'data-toggle'=>"toggle",
                    'data-on'=> 'oui',
                    'data-off'=>'non',
                    'data-onstyle'=>"success",
                    'data-offstyle'=>"danger",
                    'novalidate' => 'novalidate'
                ]
            ])

            /*->add('staffAdviser', EntityType::class, [
                'label'=>'publié par',
                'class' => ShiniStaff::class,
                'choice_label' => 'nickName'
            ])*/
            ->add('submit',SubmitType::class,[
               'label'=>'Update',
                'attr'=>[
                    'class' => "btn btn-success"
                ]
            ])


        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ShiniOffer::class,
        ]);
    }
}
