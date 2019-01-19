<?php
/**
 * Created by PhpStorm.
 * User: connector
 * Date: 10/01/2019
 * Time: 10:42
 */

namespace App\Controller;


use App\Entity\ShiniPlayer;
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
 * @Route("/connexion", name="security")
 */
class SecurityController extends AbstractController
{

    /**
     * Sign in or sign up actions (provided by Symfony)
     * Redirect to page of the user (player or a staff member).
     *
     * @param Request $request
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     *
     * @Route("/signinup", name=".signinup", methods={"GET","POST"})
     */
    public function signInUp(Request $request, UserPasswordEncoderInterface $userPasswordEncoder, AuthenticationUtils $authenticationUtils):Response
    {
        //TODO: UserPasswordEncoderInterface n'est pas utilisé, a supprimé ?
        if($this->getUser())
        {
            if((in_array('ROLE_PLAYER', $this->getUser()->getRoles())))
            {
                return $this->redirectToRoute('shini.player.connect');
            }
            else
            {
                return $this->redirectToRoute('shini.staff.connect');
            }
        }

        $shiniPlayer = new ShiniPlayer();
        $formSignUp = $this->createForm(ShiniSignInType::class, $shiniPlayer);
        $formSignUp->handleRequest($request);

        if ($formSignUp->isSubmitted() && $formSignUp->isValid()){

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($shiniPlayer);
            $entityManager->flush();
            $this->addFlash('success','Félicitation, vous pouvez vous connecter');

        }

        $formSignIn = $this->createForm(ShiniLoginType::class,[
            'email'=>$authenticationUtils->getLastUsername(),
        ]);

        $error = $authenticationUtils->getLastAuthenticationError();

        //TODO: supprimer cette variable inutilisée ?
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('shini_gami/connexion.htlm.twig',[
            'player'=> $shiniPlayer,
            'formSignUp'=> $formSignUp->createView(),
            'formSignIn'=> $formSignIn->createView(),
            'error' => $error

        ]);
    }

    /**
     * Validation (provided by Symfony).
     *
     * #@Route("/validate", name=".validate")
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
}