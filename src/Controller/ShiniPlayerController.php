<?php

namespace App\Controller;

use App\Entity\ShiniPlayer;
use App\Form\SearchPlayerCodeType;
use App\Form\SearchPlayerType;
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


    /**
     * @Route("/",name="player.index")
     * @return Response
     */
    public function index():Response
    {
        return $this->render('shini_player/index.html.twig');
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
     * @param Request $request
     * @param ShiniPlayer $shiniPlayer
     * @return Response
     */
    public function delete(Request $request, ShiniPlayer $shiniPlayer): Response
    {
        if ($this->isCsrfTokenValid('delete'.$shiniPlayer->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($shiniPlayer);
            $entityManager->flush();
        }

        return $this->redirectToRoute('shini.player.list');
    }

    /**
     * Search player with card code or username($nickname)
     * Recherche les utilisateurs par leur nom ou pseudo
     * @param Request $request
     * @param ShiniPlayerRepository $shiniPlayerRepository
     * @return Response
     * @Route("/searchByNickname",name="search.player")
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
            return $this->render('shini_player/show.html.twig',['shini_player'=>$shiniPlayerCardCode]);
        }

        return $this->render('shini_player/searchPlayerByNickname.twig',[

            'form' => $form->createView(),
            'formCode' => $formCode->createView()
        ]);
    }






}
