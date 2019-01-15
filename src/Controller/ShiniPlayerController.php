<?php

namespace App\Controller;

use App\Entity\ShiniPlayer;
use App\Form\ShiniPlayerEditType;
use App\Form\ShiniPlayerType;
use App\Repository\ShiniPlayerRepository;
use function Sodium\add;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/shiniPlayer")
 */
class ShiniPlayerController extends AbstractController
{
    const CENTER_CODE = '360';



    /**
     * @Route("/new", name="/shini_player_new", methods={"GET","POST"})
     * @param Request $request
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     * @return Response
     */
    public function new(Request $request, UserPasswordEncoderInterface $userPasswordEncoder): Response
    {
        $shiniPlayer = new ShiniPlayer();
        $form = $this->createForm(ShiniPlayerType::class, $shiniPlayer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //$shiniPlayer->setPassword($userPasswordEncoder->encodePassword($shiniPlayer,$shiniPlayer->getPassword()));
            /*$clientCode = rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9);
            $centerCodeForCheckSum = intval(self::CENTER_CODE);
            $clientCodeForCheckSum = intval($clientCode);
            $checksum =($centerCodeForCheckSum+$clientCodeForCheckSum)%9;*/
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($shiniPlayer);
            $entityManager->flush();

            return $this->redirectToRoute('shini_player_index');
        }

        return $this->render('shini_player/new.html.twig', [
            'shini_player' => $shiniPlayer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id<\d+>}", name="shini_player_show", methods={"GET"})
     * @param ShiniPlayer $shiniPlayer
     * @return Response
     */
    public function show(ShiniPlayer $shiniPlayer): Response
    {
        return $this->render('shini_player/show.html.twig', ['shini_player' => $shiniPlayer]);
    }

    /**
     * @Route("/{id}/edit", name="shini_player_edit", methods={"GET","POST"})
     * @param Request $request
     * @param ShiniPlayer $shiniPlayer
     * @return Response
     */
    public function edit(Request $request, ShiniPlayer $shiniPlayer): Response
    {
        $form = $this->createForm(ShiniPlayerEditType::class, $shiniPlayer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {



            $this->getDoctrine()->getManager()->flush();
           $this->addFlash('success','Votre profil est modifié');

            return $this->redirectToRoute('shini.player.list', ['id' => $shiniPlayer->getId()]);
        }

        return $this->render('shini_player/edit.html.twig', [
            'shini_player' => $shiniPlayer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="shini_player_delete", methods={"DELETE"})
     */
    public function delete(Request $request, ShiniPlayer $shiniPlayer): Response
    {
        if ($this->isCsrfTokenValid('delete'.$shiniPlayer->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($shiniPlayer);
            $entityManager->flush();
        }

        return $this->redirectToRoute('shini_player_index');
    }

    /**
     * @return Response
     * @Route("/searchByNickname",name="searchByNickname")
     */
    public function searchPlayerByNickname()
    {
       return $this->render('shini_player/searchPlayerByNickname.twig');
    }


    /**
     * @param Request $request
     * @param ShiniPlayerRepository $shiniPlayerRepository
     * @Route("/foundPlayerByPseudo",name="foundPlayerByPseudo")
     * @return Response
     */
    public function findPlayerByNickname(Request $request, ShiniPlayerRepository $shiniPlayerRepository)
    {
       $shiniPlayerPseudo = $request->request->get('foundPlayerByNickname');

       $ShiniPlayer = $shiniPlayerRepository->findOneBy(['nickName' =>$shiniPlayerPseudo]);

       return $this->render('shini_player/show.html.twig',['shini_player'=>$ShiniPlayer]);
    }
}
