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
use App\Entity\ShiniStaff;
use App\Form\ShiniSignInType;
use App\Form\ShiniLoginType;
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
     * Sign in or sign up actions (provided by Symfony)
     * Redirect to page of the actions (player or a staff member).
     *
     * @param Request $request
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     *
     * @Route("/signinup/{sign<in|up>}", name=".signinup", methods={"GET","POST"})
     */
    public function signInUp(Request $request,
                             UserPasswordEncoderInterface $userPasswordEncoder,
                             AuthenticationUtils $authenticationUtils,
                                string $sign = null):Response
    {
  /*      $user = $this->getUser();
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
        }*/

        // Only player can susbcribe online,
        // staffs are created by admin,
        // and admin is created by...(ni dieu, ni maître).
        
        $player = new ShiniPlayer();
        $formSignUp = $this->createForm(ShiniSignInType::class, $player);
        $formSignUp->handleRequest($request);

        if ($formSignUp->isSubmitted() && $formSignUp->isValid()){
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($player);
            $entityManager->flush();

            // Todo : Ajouter ce flash dans la redirection.
            //$this->addFlash('success','Félicitation, vous pouvez vous connecter');
            return $this->redirectToRoute('shini.player.profile');
        }

        $formSignIn = $this->createForm(ShiniLoginType::class,[
            'email'=>$authenticationUtils->getLastUsername(),
        ]);

        $error = $authenticationUtils->getLastAuthenticationError();
        
        $lastUsername = $authenticationUtils->getLastUsername();

        if ($sign == "up")
        {
            $form = $formSignUp->createView();
            $title = "Rejoignez l'aventure Shinigami";
        }
        else
        {
            $form = $formSignIn->createView();
            $title = "Connectez-vous";
        }

        return $this->render('page/signinup.html.twig',[
            'player'=> $player,
            'form'=> $form,
            'error' => $error,
            'title' => $title,
            'sign' => $sign
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
}