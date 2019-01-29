<?php

namespace App\Controller;


use App\Entity\ShiniAdmin;
use App\Entity\ShiniCard;
use App\Entity\ShiniPlayer;
use App\Entity\ShiniStaff;
use App\Form\SearchPlayerCodeType;
use App\Form\SearchPlayerType;
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
     * Search player with card code or username($nickname) (ROLE_STAFF).
     * Recherche les utilisateurs par leur nom ou pseudo
     *
     * @param Request $request
     * @param ShiniPlayerRepository $shiniPlayerRepository
     * @return Response
     *
     * @Route("/search", name=".search")
     */
    public function searchPlayer(Request $request, ShiniPlayerRepository $shiniPlayerRepository)
    {
        $form= $this->createForm(SearchPlayerType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $data = $form->getData();
            $shiniPlayerPseudo = $data['nickName'];

            $shiniPlayer = $shiniPlayerRepository->findOneBy(['nickName' =>$shiniPlayerPseudo]);

            if($shiniPlayer=== null)
            {
                $this->addFlash('danger','l\'utilisateur '.$shiniPlayerPseudo. ' n\'existe pas');
                return $this->redirectToRoute('search.player');
            }

            return $this->render('shini_player/show.html.twig',['shini_player'=>$shiniPlayer]);
        }
        $formCode = $this->createForm(SearchPlayerCodeType::class);
        $formCode->handleRequest($request);
        if($formCode->isSubmitted() && $formCode->isValid())
        {
            $data = $formCode->getData();
            $cardCode = $data['cardCode'];
            $shiniPlayerCardCode = $shiniPlayerRepository->findOneBy(['cardCode' =>$cardCode]);
            if($shiniPlayerCardCode=== null)
            {
                $this->addFlash('danger','Il n\'y a pas d\'enregistrement corresondant au numéro '.$cardCode);
                return $this->redirectToRoute('search.player');
            }
            return $this->render('entity/user/show.html.twig',['shini_player'=>$shiniPlayerCardCode]);
        }

        return $this->render('entity/user/search_player.html.twig',[
            'form' => $form->createView(),
            'formCode' => $formCode->createView()
        ]);
    }

    /**
     * Generate a card code (ROLE_STAFF).
     *
     * @param Request $request
     * @param ShiniPlayer $player
     * @param ShiniCenterRepository $shiniCenterRepository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/shiniplayer/{id<\d+>}/card", name=".searchCard")
     */
    public function cardGenerator(Request $request, ShiniPlayer $player, ShiniCenterRepository $shiniCenterRepository)
    {
        $searchCard = $player->getCardCode();

        if ($searchCard !== null) {
            $this->addFlash('danger', 'Vous avez déjà un numéro de carte pour ce compte');

            return $this->redirectToRoute('shini_player_edit', ['id' => $player->getId()]);
        }

        $card = new ShiniCard();

        $staff = $this->getUser();

        # Le code client est égale à l'id
        $clientCode = $player->getId();
        $centerCodeForCheckSum = $staff->getCenter()->getCode();
        /*$clientCodeForCheckSum = $staff->getCardCode();*/
        $checksum = ($centerCodeForCheckSum + $clientCode) % 9;
        $card->setCenter($staff->getCenter());
        $card->setPlayerCode($clientCode);
        $card->setChecksum($checksum);
        $card->setPlayer($player);
        $player->setCardCode($clientCode);

        $em = $this->getDoctrine()->getManager();
        $em->persist($card);
        $em->flush();

        $this->addFlash('success', 'carte générée !');

        return $this->redirectToRoute('shini_player_show', ['id' => $player->getId()]);
    }
}
