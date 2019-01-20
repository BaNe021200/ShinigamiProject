<?php

namespace App\Controller;

use App\Entity\ShiniPlayer;
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
 * @Route("/player", name="shini.player")
 */
class PlayerController extends AbstractController
{
    const CENTER_CODE = '360';

    /**
     * Show own profile to connected player (ROLE_PLAYER).
     *
     * @return Response
     *
     * @Route("/", name=".connect", methods={"GET"})
     */
    public function connect(Request $request): Response
    {
        //Get the connected user and show his profile
        $player = $this->getUser();

        $form = $this->createForm(ShiniPlayerType::class, $player, [
            'validation_groups'=>['Default']
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            //$shiniStaff->setPassword($userPasswordEncoder->encodePassword($shiniStaff,$shiniStaff->getPassword()));
            $em= $this->getDoctrine()->getManager();
            $em->persist($player);
            $em->flush();
            $this->addFlash('success','Votre profil a été mis à jour');

            return $this->redirectToRoute('profile.html.twig');
        }

        return $this->render('profile.html.twig',[
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
     * Show all players registered, for ROLE_STAFF only.
     *
     * @param ShiniPlayerRepository $rep
     * @return Response
     *
     * @Route("/staff/list", name=".list", methods={"GET"})
     */
    public function list(ShiniPlayerRepository $rep): Response
    {
        return $this->render('staff/list_players.html.twig', ['players' => $rep->findAll()]);
    }

    /**
     * Create a player profile (ANONYMOUS).
     * TODO: faire que cette page soit accessible pour anonymous
     *
     * @param Request $request
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     * @return Response
     *
     * @Route("/new", name=".new", methods={"GET","POST"})
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

        return $this->render('player/new.html.twig', [
            'player' => $player,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Player edit his own page (ROLE_PLAYER ?only?)
     * TODO: Un staff peut-il modifier une page player ?
     *
     * @param Request $request
     * @param ShiniPlayer $shiniPlayer
     * @return Response
     *
     * @Route("/{id}/edit", name=".edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ShiniPlayer $player): Response
    {
        $form = $this->createForm(ShiniPlayerEditType::class, $player);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success','Votre profil est modifié');

            //TODO : revoir la redirection
            return $this->redirectToRoute('shini.player.index', ['id' => $player->getId()]);
        }

        return $this->render('player/edit.html.twig', [
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

        return $this->redirectToRoute('shini.player.index');
    }

    /**
     * TODO: faire la doc
     *
     * @return Response
     *
     * @Route("/staff/searchByNickname", name=".searchByNickname")
     */
    public function searchPlayerByNickname()
    {
        return $this->render('staff/searchPlayerByNickname.twig');
    }

    /**
     * TODO: faire la doc
     *
     * @param Request $request
     * @param ShiniPlayerRepository $rep
     * @return Response
     *
     * @Route("/staff/foundPlayerByPseudo", name=".searchByPseudo")
     */
    public function findPlayerByNickname(Request $request, ShiniPlayerRepository $rep)
    {
        $shiniPlayerPseudo = $request->request->get('foundPlayerByNickname');


        $ShiniPlayer = $shiniPlayerRepository->findOneBy(['nickName' =>$shiniPlayerPseudo]);


        if($ShiniPlayer=== null)
        {
            $this->addFlash('danger','l\'utilisateur '.$shiniPlayerPseudo. ' n\'existe pas');
            return $this->redirectToRoute('shini.staff.searchByNicknameInStaffWay');
        }

        return $this->render('staff/showPlayerByNickname.html.twig',['player'=>$ShiniPlayer]);

    }

    /**
     * TODO: faire la doc
     *
     * @param Request $request
     * @param ShiniPlayer $player
     * @param ShiniCenterRepository $shiniCenterRepository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/{id<\d+>}/staff/search_card", name=".searchcard")
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
