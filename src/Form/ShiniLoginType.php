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
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class ShiniLoginType extends AbstractType
{
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGen;

    /**
     * ShiniLoginType constructor.
     * @param UrlGeneratorInterface $urlGen
     */
    public function __construct(UrlGeneratorInterface $urlGen)
    {
        $this->urlGen = $urlGen;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email',EmailType::class,[
                'constraints' => [
                    new NotBlank(),
                    new Email(['message' => 'Votre adresse "{{value}}" n\'est pas valide'])
                ],
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
            ->add('signin',SubmitType::class,[
                'label'=>'soumettre'
            ])
            ->setAction($this->urlGen->generate('secure.validate'))
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