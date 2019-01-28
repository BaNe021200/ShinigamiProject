<?php

namespace App\Controller;


use App\Entity\ShiniAdmin;
use App\Entity\ShiniPlayer;
use App\Entity\ShiniStaff;
use App\Form\ShiniPlayerEditType;
use App\Form\ShiniPlayerType;
use App\Repository\ShiniCenterRepository;
use App\Repository\ShiniPlayerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Player routes reachable with ROLE_PLAYER.
 *
 * @Route("/player", name="shini.player")
 */
class PlayerController extends AbstractController
{
    /**
     * Connected player Show/edit his profile (ROLE_PLAYER).
     * Nobody else can edit his profile.
     *
     * @return Response
     *
     * @Route("/", name=".profile", methods={"GET", "POST"})
     *
     */
    public function showEditProfile(Request $request): Response
    {
        // Get the user
        $user = $this->getUser();

        if($user)
        {
            if (is_a($user, ShiniStaff::class))
            {
                return $this->redirectToRoute('shini.staff.profile');
            }
            else if (is_a($user, ShiniAdmin::class))
            {
                return $this->redirectToRoute('shini.admin.profile');
            }
        }

        // Only a player can edit his profile
        $form = $this->createForm(ShiniPlayerType::class, $user, [
            'validation_groups'=>['Default']
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            //$shiniStaff->setPassword($userPasswordEncoder->encodePassword($shiniStaff,$shiniStaff->getPassword()));
            $em= $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->addFlash('success','Votre profil a été mis à jour');
/*            return $this->render('profile.html.twig', [
                'user' => $player
            ]);*/
        }
        return $this->render('entity/user/profile.html.twig',[
            'form'=> $form->createView()
        ]);
    }

    /**
     * Show a player profile (ROLE_PLAYER).
     *
     * @param ShiniPlayer $shiniPlayer
     * @return Response
     *
     * @Route("/{id<\d+>}", name=".show", methods={"GET"})
     */
    public function show(ShiniPlayer $player): Response
    {
        return $this->render('player/show.html.twig', ['player' => $player]);
    }

    /**
     * Show all players registered (ROLE_PLAYER).
     *
     * @param ShiniPlayerRepository $rep
     * @return Response
     *
     * @Route("/list", name=".list", methods={"GET"})
     */
    public function list(ShiniPlayerRepository $rep): Response
    {
        return $this->render('entity/user/list.html.twig', ['items' => $rep->findAll()]);
    }

    /**
     * Create a player profile (ANONYMOUS).
     * TODO: action à supprimer, car inutile.
     *
     * @param Request $request
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     * @return Response
     *
     * #@Route("/new", name=".new", methods={"GET","POST"})
     */
    public function new(Request $request, UserPasswordEncoderInterface $userPasswordEncoder): Response
    {
        $player = new ShiniPlayer();
        $form = $this->createForm(ShiniPlayerType::class, $player);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //$shiniPlayer->setPassword($userPasswordEncoder->encodePassword($shiniPlayer,$shiniPlayer->getPassword()));
            /*$clientCode = rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9);
            $centerCodeForCheckSum = intval(self::CENTER_CODE);
            $clientCodeForCheckSum = intval($clientCode);
            $checksum =($centerCodeForCheckSum+$clientCodeForCheckSum)%9;*/
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($player);
            $entityManager->flush();

            return $this->redirectToRoute('shini.player.index');
        }

        return $this->render('page/new.html.twig', [
            'player' => $player,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Delete a player from database (ROLE_STAFF).
     *
     * @param Request $request
     * @param ShiniPlayer $player
     * @return Response
     *
     * @Route("/{id}/staff/delete", name=".delete", methods={"DELETE"})
     */
    public function delete(Request $request, ShiniPlayer $player): Response
    {
        if ($this->isCsrfTokenValid('delete'.$player->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($player);
            $entityManager->flush();
        }

        return $this->redirectToRoute('shini.player.list');
    }

    /**
     * TODO: faire la doc
     * TODO: Ben, cette fonctionnalité est ouverte aux players, tra la la
     *
     * @return Response
     *
     * @Route("/searchByNickname", name=".searchByNickname")
     */
    public function searchPlayerByNickname()
    {
        return $this->render('staff/searchPlayerByNickname.twig');
    }

    /**
     * TODO: faire la doc
     * TODO: Ben, cette fonctionnalité est ouverte aux players, tsoin, tsoin
     *
     * @param Request $request
     * @param ShiniPlayerRepository $rep
     * @return Response
     *
     * @Route("/foundPlayerByPseudo", name=".searchByPseudo")
     */
    public function findPlayerByNickname(Request $request, ShiniPlayerRepository $rep)
    {
        $pseudo = $request->request->get('foundPlayerByNickname');


        $player = $rep->findOneBy(['nickName' => $rep]);


        if($player=== null)
        {
            $this->addFlash('danger','l\'utilisateur '.$pseudo. ' n\'existe pas');
            return $this->redirectToRoute('shini.staff.searchByNicknameInStaffWay');
        }

        return $this->render('staff/showPlayerByNickname.html.twig',['player'=>$player]);

    }

    /**
     * TODO: faire la doc
     *
     * @param Request $request
     * @param ShiniPlayer $player
     * @param ShiniCenterRepository $shiniCenterRepository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/{id<\d+>}/staff/search_card", name=".staff.searchcard")
     */
    public function cardGenerator(Request $request, ShiniPlayer $player, ShiniCenterRepository $shiniCenterRepository)
    {
        $searchCard =  $player->getCardCode();

        if($searchCard !== null)
        {
            $this->addFlash('danger','Vous avez déjà un numéro de carte pour ce compte');

            return $this->redirectToRoute('shini.player.edit', ['id'=>$player->getId()]);
        }

        $unique = uniqid('', true);
        $file_name = substr($unique, strlen($unique) - 6, strlen($unique));
        dd($unique);
        dd($file_name);
        $card = new ShiniCard();
        $shinicenter = $shiniCenterRepository->findOneBy(['code'=>360]);
        //dd($shinicenter);

        $clientCode = rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9);
        $centerCodeForCheckSum = intval(self::CENTER_CODE);
        $clientCodeForCheckSum = intval($clientCode);
        $checksum =($centerCodeForCheckSum+$clientCodeForCheckSum)%9;
        $card->setCenter($shinicenter);
        $card->setPlayerCode($clientCode);
        $card->setChecksum($checksum);

        $em = $this->getDoctrine()->getManager();
        $em->persist($card);
        $em->flush();

        $this->addFlash('success', 'carte générée !');

        return $this->render('staff/edit.html.twig');
    }
}
