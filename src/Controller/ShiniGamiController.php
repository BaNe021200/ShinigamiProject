<?php

namespace App\Controller;

use App\Entity\ShiniPlayer;
use App\Form\ShiniLaserSignInType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
     * @Route("/connexion",name="connexion", methods={"GET","POST"})
     * @param Request $request
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     * @return Response
     */
    public function Log(Request $request, UserPasswordEncoderInterface $userPasswordEncoder):Response
    {
        $shiniPlayer = new ShiniPlayer();
        $form = $this->createForm(ShiniLaserSignInType::class, $shiniPlayer);
        $form->handleRequest($request);



        if ($form->isSubmitted() && $form->isValid()){

            $shiniPlayer->setPassword($userPasswordEncoder->encodePassword($shiniPlayer,$shiniPlayer->getPassword()));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($shiniPlayer);
            $entityManager->flush();
            $this->addFlash('notice','Félicitation, vous pouvez vous connecter');
            return $this->redirectToRoute('shini_player_index');
        }

        return $this->render('shini_gami/connexion.htlm.twig',[

            'shini_player'=> $shiniPlayer,
            'form'=> $form->createView()

        ]);

    }

}
