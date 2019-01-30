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
use App\Form\ShiniSignInType;
use App\Form\ShiniLoginType;
use App\Repository\ShiniPlayerRepository;
use App\Service\EmailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

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
     *
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
            return $this->redirectToRoute('shini.success');
        }

        return $this->render('page/signinup.html.twig', [
            'user' => $player,
            'form' => $form->createView(),
            'error' => $authenticationUtils->getLastAuthenticationError(),
            'title' => $title
        ]);
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
     * @param Request $request
     * @param ShiniPlayerRepository $playerRepository
     * @return Response
     * @Route("/forgottePassword",name=".forgottenPassword")
     */
    public function forgottenPassWord(Request $request, ShiniPlayerRepository $playerRepository )
    {
        $form = $this->createForm(ForgottenPassordType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $mailInComing = $form->getData();
            $isPlayerEmailExist = $playerRepository->findOneBy(["mail" => $mailInComing['email'] ]);

            if($isPlayerEmailExist){
                $this->addFlash('info','un message vous a été envoyé avec la démarche à suivre afin de changer votre mot de passe');
            }
            else{
                $this->addFlash('danger','cet email ne correspond à aucun compte');
            }

            return $this->render('page/reset_password.html.twig');

        }

        return $this->render('page/forgottenPassWord.html.twig',[

            'form'=> $form->createView()
        ]);

    }

    public function resetPassword()
    {

    }
}