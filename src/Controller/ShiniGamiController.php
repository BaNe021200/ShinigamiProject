<?php

namespace App\Controller;

use App\Entity\ShiniOffer;
use App\Entity\ShiniPlayer;
use App\Form\ShiniLaserSignInType;

use App\Form\ShiniLoginType;
use App\Repository\ShiniOffersRepository;
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
     * @param ShiniOffersRepository $offersRepository
     * @return Response
     */
    public function index(ShiniOffersRepository $offersRepository):Response
    {

        $offerOnLine = $offersRepository->findOnLine();

        return $this->render('shini_gami/index.html.twig', [
            'onLines'=>$offerOnLine,

        ]);
    }



    /**
     * @Route("/about",name="about")
     */
    public function about()
    {
       return $this->render('shini_gami/about.htlm.twig',[
           'about' =>'about'
       ]);
    }


    /**
     * @Route("/offers",name="offers")
     * @param ShiniOffersRepository $offersRepository
     * @return Response
     */
    public function offers(ShiniOffersRepository $offersRepository):Response
    {
        $shiniOffers = $offersRepository->findVisible();

        return $this->render('shini_gami/offers.html.twig',[
            'offer' =>'offer',
            'shini_offers' =>$shiniOffers


        ]);
    }

    /**
     * @Route("/{slug}-{id}",name="shiniGami.offer.show", methods={"GET"},requirements={"slug": "[a-z0-9\-]*"})
     * @param ShiniOffer $shiniOffer
     * @return Response
     */
    public function offerShow(ShiniOffer $shiniOffer):Response
    {
      return $this->render('shini_gami/showOffer.twig',[
          'shini_offer' =>$shiniOffer
      ]);
    }

    /**
     * @Route("/contact",name="contact")
     */
    public function contact()
    {
        return $this->render('shini_gami/contact.htlm.twig',['contact'=>'contact']);
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
