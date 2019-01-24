<?php
/**
 * Created by PhpStorm.
 * User: connector
 * Date: 10/01/2019
 * Time: 10:42
 */

namespace App\Controller;


use App\Form\ShiniLoginType;
use App\Form\ShiniPlayerLoginType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class ShiniSecurityController extends AbstractController
{
    /**
     * @Route("/connexion",name="security.connexion")
     * @param AuthenticationUtils $authenticationUtils
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function logIn(AuthenticationUtils $authenticationUtils)
    {
       if($this->getUser())
       {
           if((in_array('ROLE_STAFF', $this->getUser()->getRoles())) ||(in_array('ROLE_ADMIN', $this->getUser()->getRoles()))) {
               return $this->redirectToRoute('shini_staff_index');
           } else {
               return $this->redirectToRoute('shini.player.list');
           }
       }

       $form = $this->createForm(ShiniLoginType::class,[
         'email'=>$authenticationUtils->getLastUsername()
       ]);

       $error = $authenticationUtils->getLastAuthenticationError();

       $lastUsername = $authenticationUtils->getLastUsername();

       return $this->render('shini_gami/connexion.htlm.twig',[
           'logInForm'=> $form->createView(),
           'error'=>$error
       ]);
    }

    /**
     * @Route("/logOut", name="logOut")
     */
    public function logOut()
    {

    }

}