<?php
namespace App\Controller;
use App\Entity\ShiniOffer;
use App\Entity\ShiniPlayer;
use App\Form\ShiniLaserSignInType;
use App\Form\ShiniLoginType;
use App\Repository\ShiniOffersRepository;
use App\Repository\ShiniPlayerRepository;
use App\Service\EmailService;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Validator\Constraints\Date;
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
    public function index(ShiniOffersRepository $offersRepository): Response
    {
        $offerOnLine = $offersRepository->findOnLine();
        return $this->render('shini_gami/index.html.twig', [
            'onLines' => $offerOnLine,
        ]);
    }

    /**
     * @Route("/about",name="about")
     */
    public function about()
    {
        return $this->render('shini_gami/about.htlm.twig', [
            'about' => 'about'
        ]);
    }

    /**
     * @Route("/offers",name="offers")
     * @param ShiniOffersRepository $offersRepository
     * @return Response
     */
    public function offers(ShiniOffersRepository $offersRepository): Response
    {
        $shiniOffers = $offersRepository->findVisible();
        return $this->render('shini_gami/offers.html.twig', [
            'offer' => 'offer',
            'shini_offers' => $shiniOffers

        ]);
    }
    /**
     * @Route("/{slug}-{id}",name="shiniGami.offer.show", methods={"GET"},requirements={"slug": "[a-z0-9\-]*"})
     * @param ShiniOffer $shiniOffer
     * @return Response
     */
    public function offerShow(ShiniOffer $shiniOffer): Response
    {
        return $this->render('shini_gami/showOffer.twig', [
            'shini_offer' => $shiniOffer
        ]);
    }
    /**
     * @Route("/contact",name="contact")
     */
    public function contact()
    {
        return $this->render('shini_gami/contact.htlm.twig', ['contact' => 'contact']);
    }

    /**
     * @Route("/signInUp",name="signInUp", methods={"GET","POST"})
     * @Route("/signInUp/{logout}")
     * @param Request $request
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     * @param AuthenticationUtils $authenticationUtils
     * @param EmailService $email
     * @param null $logout
     * @return Response
     * @throws \Exception
     */
    public function signInUp(Request $request, UserPasswordEncoderInterface $userPasswordEncoder, AuthenticationUtils $authenticationUtils, EmailService $email, $logout=null): Response
    {
        /*$myTranslator = new Translator('fr_FR');
        $myTranslator->addResource('array', [
            'Invalid credentials' => 'Utilisateur inconnu au bataillon !',
        ], 'fr_FR');*/

        //$myTranslator->addResource('yml', 'translations/security.fr.yml', 'fr_FR');

        if($logout ==='logout')
        {
            /*$this->addFlash('info','Au revoir vous êtes maintenant déconnecté !');

            return $this->redirectToRoute('home');*/

          return $this->render('shini_player/logout.html.twig');

        }

        if ($this->getUser()) {
            if ((in_array('ROLE_PLAYER', $this->getUser()->getRoles()))) {
               $this->addFlash('success','Bonjour '.$this->getUser()->getName().' vous êtes maintenant connecté');
                return $this->redirectToRoute('player.index');
            } else {
                $this->addFlash('success','Bonjour '.$this->getUser()->getName().' vous êtes maintenant connecté');

                return $this->redirectToRoute('shini_staff_index');
            }
        }
        else{

        }
        $shiniPlayer = new ShiniPlayer();
        //$shiniPlayer
        $formSignUp = $this->createForm(ShiniLaserSignInType::class, $shiniPlayer, [
            'validation_groups' => ['insertion', 'Default']
        ]);
        $formSignUp->handleRequest($request);

        if ($formSignUp->isSubmitted() && $formSignUp->isValid()) {
            //$shiniPlayer->setPassword($userPasswordEncoder->encodePassword($shiniPlayer,$shiniPlayer->getPassword()));
            $token = uniqid('', true);
            $entityManager = $this->getDoctrine()->getManager();
            $shiniPlayer->setConfirmationToken($token);
            $entityManager->persist($shiniPlayer);
            $entityManager->flush();
            $this->addFlash('success', 'Bienvenue parmi nous');
            $email->email($shiniPlayer);
            return $this->redirectToRoute('signInUp');
        }
        $formSignIn = $this->createForm(ShiniLoginType::class, [
            'email' => $authenticationUtils->getLastUsername(),
        ]);

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        /*if($formSignIn->isSubmitted() && $formSignIn->isValid())
        {

            $this->addFlash('success','Bonjour '.$shiniPlayer->getName().' vous êtes maintenant connecté');if($shiniPlayer->getConfirmedAt());
            return $this->redirectToRoute('player.index');

        }*/

        return $this->render('shini_gami/connexion.htlm.twig', [
            'shini_player' => $shiniPlayer,
            'formSignUp' => $formSignUp->createView(),
            'formSignIn' => $formSignIn->createView(),

            'error' => $error
        ]);
    }

    /**
     * @param Request $request
     * @param $userId
     * @param $token
     * @param TokenStorageInterface $storage
     * @param ShiniPlayerRepository $repository
     * @param ObjectManager $em
     * @param SessionInterface $session
     * @param EventDispatcherInterface $eventDispatcher
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     * @Route("/confirm/{userId}-{token}",name="email.confirmation",methods={"GET"})
     */
    public function emailConfirm(Request $request, $userId, $token, TokenStorageInterface $storage, ShiniPlayerRepository $repository,ObjectManager $em, SessionInterface $session,EventDispatcherInterface $eventDispatcher)
    {
        $player = $repository->find($userId);

        if ($player && $player->getConfirmationToken() === $token) {
            $dateConfirm = new \DateTime();
            $player->setConfirmationToken(null);
            $player->setConfirmedAt($dateConfirm);

            $em->flush();
            $this->addFlash('info','bienvenue, vous maintenant authentifié');

            $token = new UsernamePasswordToken($player, null, 'main', $player->getRoles());
            $storage->setToken($token);

            $session->set('_security_main', serialize($token));
            $event = new InteractiveLoginEvent($request, $token);
            $eventDispatcher->dispatch("security.interactive_login", $event);

            return $this->redirectToRoute('player.index');
        }
        else
        {
            $this->addFlash('danger','ce token n\'est plus valide');
        }


        return $this->redirectToRoute('player.index');
    }


    /**
     * @Route("/logOut",name="logOut")
     */
    public function logOut()
    {
        $this->render('shini_player/logout.html.twig');
    }
    public function validation()
    {
    }
}
