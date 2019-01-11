<?php

namespace App\Controller;

use App\Entity\ShiniPlayer;
use App\Form\ShiniLaserSignInType;

use App\Form\ShiniLoginType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * @Route("/shiniGami")
 */
class ShiniGamiController extends AbstractController
{
    /**
     * @Route("/", name="shini_gami")
     */
    public function index()
    {
        return $this->render('shini_gami/index.html.twig', [
            'controller_name' => 'ShiniGamiController',
        ]);
    }

    /**
     * @Route("/about",name="about")
     */
    public function about()
    {
       return $this->render('shini_gami/about.htlm.twig');
    }


    /**
     * @Route("/offers",name="offers")
     */
    public function offers()
    {
        return $this->render('shini_gami/offers.html.twig');
    }

    /**
     * @Route("/contact",name="contact")
     */
    public function contact()
    {
        return $this->render('shini_gami/contact.htlm.twig');
    }

    /**
     * @Route("/signInUp",name="signInUp", methods={"GET","POST"})
     * @param Request $request
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function signInUp(Request $request, UserPasswordEncoderInterface $userPasswordEncoder,AuthenticationUtils $authenticationUtils):Response
    {
        if($this->getUser())
        {
            if((in_array('ROLE_PLAYER', $this->getUser()->getRoles()))) {
                return $this->redirectToRoute('shini_player_index');
            } else {
                return $this->redirectToRoute('shini_staff_index');
            }
        }

        $shiniPlayer = new ShiniPlayer();
        //$shiniPlayer
        $formSignUp = $this->createForm(ShiniLaserSignInType::class, $shiniPlayer);
        $formSignUp->handleRequest($request);



        if ($formSignUp->isSubmitted() && $formSignUp->isValid()){

            //$shiniPlayer->setPassword($userPasswordEncoder->encodePassword($shiniPlayer,$shiniPlayer->getPassword()));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($shiniPlayer);
            $entityManager->flush();
            $this->addFlash('success','Félicitation, vous pouvez vous connecter');

        }

        $formSignIn = $this->createForm(ShiniLoginType::class,[
            'email'=>$authenticationUtils->getLastUsername(),
        ]);

        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('shini_gami/connexion.htlm.twig',[

            'shini_player'=> $shiniPlayer,
            'formSignUp'=> $formSignUp->createView(),
            'formSignIn'=> $formSignIn->createView(),
            'error' => $error

        ]);

    }

    /**
     * @Route("/logOut",name="logOut")
     */
    public function logOut()
    {

    }

    public function validation()
    {

    }

}
