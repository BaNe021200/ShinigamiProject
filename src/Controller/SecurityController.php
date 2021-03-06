<?php
/**
 * Created by PhpStorm.
 * User: connector
 * Date: 10/01/2019
 * Time: 10:42
 */

namespace App\Controller;


use App\Entity\ShiniAdmin;
use App\Entity\ShiniPlayer;
use App\Entity\ShiniPlayerAccount;
use App\Entity\ShiniStaff;
use App\Form\ForgottenPassordType;
use App\Form\ResetPasswordType;
use App\Form\ShiniSignInType;
use App\Form\ShiniLoginType;
use App\Repository\ShiniPlayerRepository;
use App\Service\EmailService;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Null_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;


/**
 * Symfony Security.
 *
 * @Route("/connect", name="secure")
 */
class SecurityController extends AbstractController
{

    /**
     * formulaire d'inscription et de connexion gestion
     * @param Request $request
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     * @param AuthenticationUtils $authenticationUtils
     * @param EmailService $email
     * @param null $sign
     * @return Response
     * @throws \Exception
     *
     * @Route("/sign", name=".sign", methods={"GET","POST"})
     * @Route("/sign/{sign<in|up>}", name=".signinup", methods={"GET","POST"})
     */
    public function signInUp(Request $request,
                             UserPasswordEncoderInterface $userPasswordEncoder,
                             AuthenticationUtils $authenticationUtils,
                             EmailService $email,
                             $sign = null): Response
    {

        // A player wants to connect
        $player = new ShiniPlayer();

        if ($sign == "up")
        {
            $form = $this->createForm(ShiniSignInType::class, $player, [
                'validation_groups' => ['insertion', 'Default']
            ]);
            $title = "Rejoignez l'aventure Shinigami";
        }
        else
        {
            $form = $this->createForm(ShiniLoginType::class, [
                'email' => $authenticationUtils->getLastUsername(),
            ]);
            $title = "Connectez-vous";
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //$player->setPassword($userPasswordEncoder->encodePassword($player,$player->getPassword()));
            $token = uniqid('', true);
            $entityManager = $this->getDoctrine()->getManager();
            #$player->setConfirmationToken($token);

            $account = new  ShiniPlayerAccount($player);
            $account->setConfirmationToken($token);

            $entityManager->persist($player);
            $entityManager->persist($account);
            $entityManager->flush();

            $email->emailRegistry($player);

            $this->addFlash('success', 'Bienvenue chez Shinigami Laser !');
            return $this->redirectToRoute('secure.success');
        }

        return $this->render('page/signinup.html.twig', [
            'user' => $player,
            'form' => $form->createView(),
            'error' => $authenticationUtils->getLastAuthenticationError(),
            'title' => $title
        ]);
    }

    /**
     * @param Request $request
     * @param $userId
     * @param $token
     * @param TokenStorageInterface $storage
     * @param ShiniPlayerRepository $playerRepository
     * @param ObjectManager $em
     * @param SessionInterface $session
     * @param EventDispatcherInterface $eventDispatcher
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     * @Route("/confirm/{userId}-{token}",name=".email.confirmation",methods={"GET"})
     */
    public function emailConfirm(Request $request, $userId, $token, TokenStorageInterface $storage,ShiniPlayerRepository $playerRepository ,ObjectManager $em, SessionInterface $session,EventDispatcherInterface $eventDispatcher)
    {

        $player = $playerRepository->find($userId);
        $playerAccount = $player->getAccount();

        if ($playerAccount && ($playerAccount->getConfirmationToken() === $token)) {
            $dateConfirm = new \DateTime();
            $playerAccount->setConfirmationToken(null);
            $playerAccount->setConfirmedAt($dateConfirm);

            $em->flush();
            $this->addFlash('info','bienvenue, vous maintenant authentifié');

            $token = new UsernamePasswordToken($player, null, 'main', $player->getRoles());
            $storage->setToken($token);

            $session->set('_security_main', serialize($token));
            $event = new InteractiveLoginEvent($request, $token);
            $eventDispatcher->dispatch("security.interactive_login", $event);

            return $this->redirectToRoute('shini.player.profile');
        }
        else
        {
            $this->addFlash('danger','ce token n\'est plus valide');
        }


        return $this->redirectToRoute('shini.player.profile');
    }

    /**
     * Validation (provided by Symfony).
     *
     * @Route("/validate", name=".validate")
     */
    public function validation()
    {


    }

    /**
     * Sign out (provided by Symfony).
     *
     * @Route("/signout", name=".signout")
     */
    public function signOut()
    {

    }

    /**
     * Show a modal on home when user disconnect.
     *
     * @Route("/logout", name=".logout")
     */
    public function logOut()
    {
        return $this->render('page/index.html.twig', ['logout' => true]);
    }

    /**
     * After successfull login redirect user to his profile.
     *
     * @return Response
     *
     * @Route("/signinup/success", name=".success", methods={"GET","POST"})
     */
    public function success()
    {
        $user = $this->getUser();
        if($user)
        {
            if (is_a($user, ShiniPlayer::class))
            {
                return $this->redirectToRoute('shini.player.profile');
            }
            if (is_a($user, ShiniStaff::class))
            {
                return $this->redirectToRoute('shini.staff.profile');
            }
            else if (is_a($user, ShiniAdmin::class))
            {
                return $this->redirectToRoute('shini.admin.profile');
            }
        }

        return $this->redirectToRoute('shini.index');
    }

    /**
     * envoie un mail de réinitialisation de mot de passe si l'identité du player à été établie
     * @param Request $request
     * @param ShiniPlayerRepository $playerRepository
     * @param EmailService $emailService
     * @return Response
     * @Route("/forgottenPassword",name=".forgottenPassword")
     */
    public function forgottenPassWord(Request $request, ShiniPlayerRepository $playerRepository, EmailService $emailService)
    {
        $form = $this->createForm(ForgottenPassordType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $mailInComing = $form->getData();
            $isPlayerEmailExist = $playerRepository->findOneBy(["email" => $mailInComing['email'] ]);
            //dd($isPlayerEmailExist->getAccount()->getConfirmedAt());
            if($isPlayerEmailExist && ($isPlayerEmailExist->getAccount()->getConfirmedAt()!==null)){

                $token = uniqid('', true);
                $isPlayerEmailExist->getAccount()->setConfirmationToken($token);
                $isPlayerEmailExist->getAccount()->setConfirmedAt(Null);

                $em = $this->getDoctrine()->getManager();
                $em->persist($isPlayerEmailExist);
                $em->flush();

                $emailService->emailReInitialization($isPlayerEmailExist);

                $this->addFlash('info','un message vous a été envoyé avec la démarche à suivre afin de changer votre mot de passe');

                return $this->redirectToRoute('shini.index');


            }
            else{
                $this->addFlash('danger','cet email ne correspond à aucun compte');
                return $this->render('page/forgottenPassWord.html.twig',[
                    'form'=>$form->createView()
                ]);
            }

            return $this->render('page/reset_password.html.twig');

        }

        return $this->render('page/forgottenPassWord.html.twig',[

            'form'=> $form->createView()
        ]);

    }

    /**
     *
     * Formulaire du reset de mot de passe
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param TokenStorageInterface $storage
     * @param SessionInterface $session
     * @param EventDispatcherInterface $eventDispatcher
     * @param ShiniPlayerRepository $playerRepository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Exception
     * @Route("/resetPassword",name=".resetpassword")
     */
    public function resetPassword(Request $request, EntityManagerInterface $em, TokenStorageInterface $storage, SessionInterface $session, EventDispatcherInterface $eventDispatcher, ShiniPlayerRepository $playerRepository)
    {


        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);
        $userId = $request->get('userId');
        $player = $playerRepository->findOneBy(['id'=>$userId]);
        $playerConfirmAt = $player->getAccount()->getConfirmedAt();

        if($playerConfirmAt !== null) {
            $this->addFlash('danger','ce token est n\'est plus valide');
            return $this->redirectToRoute('secure.sign');
        }

        $playerToken = $player->getAccount()->getConfirmationToken();
        $confirmToken = $request->get('token');

        if ($playerToken !== $confirmToken) {
            $this->addFlash('danger', 'ce token n\'est plus valide ');
            return $this->redirectToRoute('shini.index');
        }

        if($form->isSubmitted() && $form->isValid()) {


            $player->getAccount()->setConfirmationToken(null);
            $player->getAccount()->setConfirmedAt(new \DateTime());

            $em->persist($player);
            $em->flush();

            $this->addFlash('info', 'Votre mot de passe est réinitialisé, vous pouvez vous reconnecter');

            $token = new UsernamePasswordToken($player, null, 'main', $player->getRoles());
            $storage->setToken($token);

            $session->set('_security_main', serialize($token));
            $event = new InteractiveLoginEvent($request, $token);
            $eventDispatcher->dispatch("security.interactive_login", $event);


            return $this->redirectToRoute('secure.success');


        }


       return $this->render('page/reset_password.html.twig',[

            'form'=> $form->createView()

       ]);

    }
}