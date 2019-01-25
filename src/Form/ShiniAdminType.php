<?php
/**
 * Created by PhpStorm.
 * User: connector
 * Date: 08/01/2019
 * Time: 10:08
 */

namespace App\Form;


use App\Entity\ShiniAdmin;


use Symfony\Component\OptionsResolver\OptionsResolver;

class ShiniAdminType extends ShiniPlayerType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefault('data_class',ShiniAdmin::class);
    }
}