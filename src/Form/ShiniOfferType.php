<?php

namespace App\Form;

use App\Entity\ShiniOffer;
use App\Service\ImageSaver\ImageSaver;
use App\Service\ImageSaver\ImageSaverType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShiniOfferType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class,[
                'label'=>'Nom de l\'offre',
                'attr'=>[
                    'placeholder'=>'nom de votre offre'
                ]
            ])
            ->add('price', MoneyType::class,[
                'label'=> 'prix',
                'attr'=>[
                    'placeholder'=>"prix de l'article",
                ]
            ])
            ->add('dateEnd',DateType::class,[
                'label' =>'date de fin',
                'attr' =>[
                    'placeholder'=>"date de fin",
                ]
            ])

            ->add('description',CKEditorType::class,[
                'label'=>'Description',
                'attr'=> [
                    'placeholder'=>"Description de l'offre"
                ],'config' =>[
                    'toolbar'=>'standard'
                ]
            ])
            ->add('image')
/*            ->add('shown',CheckboxType::class,[
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
            ])*/
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
            ->add('image',ImageSaverType::class,[
                'label'=> 'soumettre',
                'attr'=>[
                    'class' =>'btn btn-success'
                ]
            ])
            /*->add('staffAdviser', EntityType::class, [
                'label'=>'publié par',
                'class' => ShiniStaff::class,
                'choice_label' => 'nickName'
            ])*/

            ->add('submit',SubmitType::class,[
                'label'=> 'soumettre',
                'attr'=>[
                    'class' =>'btn btn-success'
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
