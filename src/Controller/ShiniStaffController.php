<?php

namespace App\Controller;

use App\Entity\ShiniCard;
use App\Entity\ShiniCenter;
use App\Entity\ShiniOffer;
use App\Entity\ShiniPlayer;
use App\Entity\ShiniStaff;
use App\Form\ShiniCenterType;
use App\Form\ShiniOfferType;
use App\Form\ShiniPlayerEditType;
use App\Form\ShiniPlayerType;
use App\Form\ShiniStaffEditType;
use App\Form\ShiniStaffType;
use App\Repository\ShiniCenterRepository;
use App\Repository\ShiniPlayerRepository;
use App\Repository\ShiniStaffRepository;
use function Sodium\add;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/shiniStaff")
 */
class ShiniStaffController extends AbstractController
{
    const CENTER_CODE = '360';

    /**
     * @Route("/", name="shini_staff_index", methods={"GET"})
     * @param ShiniStaffRepository $shiniStaffRepository
     * @return Response
     */
    public function index(ShiniStaffRepository $shiniStaffRepository): Response
    {
        return $this->render('shini_staff/index.html.twig', ['shini_staffs' => $shiniStaffRepository->findStaffWithCenter()]);
    }

    /**
     * @Route("/shiniPlayer/new", name="shini.staff.player_new", methods={"GET","POST"})
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

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($shiniPlayer);
            $entityManager->flush();

            return $this->redirectToRoute('shini.player.list');
        }

        return $this->render('shini_staff/shini_player_new.html.twig', [
            'shini_player' => $shiniPlayer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id<\d+>}", name="shini_staff_show", methods={"GET"})
     * @param ShiniStaff $shiniStaff
     * @return Response
     */
    public function show(ShiniStaff $shiniStaff): Response
    {
        return $this->render('shini_staff/show.html.twig', ['shini_staff' => $shiniStaff]);
    }

    /**
     * @Route("/{id}/edit", name="shini_staff_edit", methods={"GET","POST"})
     * @param Request $request
     * @param ShiniStaff $shiniStaff
     * @return Response
     */
    public function edit(Request $request, ShiniStaff $shiniStaff): Response
    {

        $form = $this->createForm(ShiniStaffEditType::class, $shiniStaff);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {



            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success','Votre profil est modifié');

            return $this->redirectToRoute('shini_staff_index', ['id' => $shiniStaff->getId()]);
        }

        return $this->render('shini_staff/edit.html.twig', [
            'shini_staff' => $shiniStaff,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="shini_staff_delete", methods={"DELETE"})
     * @param Request $request
     * @param ShiniStaff $shiniStaff
     * @return Response
     */
    public function delete(Request $request, ShiniStaff $shiniStaff): Response
    {
        if ($this->isCsrfTokenValid('delete'.$shiniStaff->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($shiniStaff);
            $entityManager->flush();
        }

        return $this->redirectToRoute('shini_staff_index');
    }




    /**
     *
     * @Route("/shiniplayer/{id<\d+>}/card",name="searchCard")
     * @param Request $request
     * @param ShiniPlayer $player
     * @param ShiniCenterRepository $shiniCenterRepository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function cardGenerator(Request $request, ShiniPlayer $player, ShiniCenterRepository $shiniCenterRepository)
    {
      $searchCard =  $player->getCardCode();

      if($searchCard !== null)
      {
          $this->addFlash('danger','Vous avez déjà un numéro de carte pour ce compte');

          return $this->redirectToRoute('shini_player_edit', ['id'=>$player->getId()]);
      }

      $card = new ShiniCard();

      $staff = $this->getUser();

      $shinicenter = $shiniCenterRepository->findOneBy(['code'=>360]);


      //$clientCode = rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9);
      $clientCode= $player->getId();
      $centerCodeForCheckSum = intval(self::CENTER_CODE);
      $clientCodeForCheckSum = intval($clientCode);
      $checksum =($centerCodeForCheckSum+$clientCodeForCheckSum)%9;
      $card->setCenter($shinicenter);
      $card->setPlayerCode($clientCode);
      $card->setChecksum($checksum);
      $card->setPlayer($player);
      $player->setCardCode($clientCode);

       $em = $this->getDoctrine()->getManager();
       $em->persist($card);
       $em->flush();

       $this->addFlash('success', 'carte générée !');

       return $this->redirectToRoute('shini_player_show', ['id'=>$player->getId()]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route("/create/new/centre",name="shiniStaff.create.new.center")
     */
    public function createNewCenter(Request $request)
    {

        $center = new ShiniCenter();
        $form = $this->createForm(ShiniCenterType::class,$center);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            //$center->setCode();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($center);
            $entityManager->flush();
            $this->addFlash('success','Le centre est bien créée');
            return $this->redirectToRoute('shini_staff_index');
        }




        return $this->render('shini_staff/newCenter.html.twig',[

            'centre'=>$center,
            'form'=> $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     * @param ShiniCenterRepository $shiniCenterRepository
     * @return Response
     * @Route("/create",name="create.new.staff")
     */
    public function createNewStaff(Request $request, UserPasswordEncoderInterface $userPasswordEncoder,ShiniCenterRepository $shiniCenterRepository):Response
    {
        $shiniStaff = new ShiniStaff();
        $shiniCenter = $shiniCenterRepository->findAll();

        //dd($shiniCenter);

        $form = $this->createForm(ShiniStaffType::class,$shiniStaff, [
            'validation_groups'=>['insertion', 'Default']
        ]);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {

            //$shiniStaff->setPassword($userPasswordEncoder->encodePassword($shiniStaff,$shiniStaff->getPassword()));

            $em= $this->getDoctrine()->getManager();
            $em->persist($shiniStaff);
            $em->flush();
            $this->addFlash('success','Le membre est créé');

            return $this->redirectToRoute('shini_staff_index');


        }

        return $this->render('shini_staff/newStaff.html.twig',[

            'form'=> $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/create/offer",name="shiniStaff.new.offer")
     */
    public function createNewOffer(Request $request):Response
    {

        $shiniOffer = new ShiniOffer();
        $form = $this->createForm(ShiniOfferType::class, $shiniOffer);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $shiniOffer->setStaffAdviser($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->persist($shiniOffer);
            $em->flush();
            $this->addFlash('success','Votre Offre est créée');
            if($shiniOffer->getShown())
            {
                $this->addFlash('info','votre offre est publiée');

            }else{
                $this->addFlash('danger','votre offre n\'est pas publiée');
            }
            if($shiniOffer->getOnfirstpage())
            {
                $this->addFlash('info','votre offre est publiée à la une');
            }else{
                $this->addFlash('danger','votre offre n\'est pas à la une');
            }

            return $this->redirectToRoute('shini.offer.index');
        }

        return $this->render('shini_staff/offerForm.html.twig',[

            'form'=> $form->createView()
        ]);

    }


    /**
     * @Route("/ShiniPlayer/list", name="shini.player.list", methods={"GET"})
     * @param ShiniPlayerRepository $shiniPlayerRepository
     * @return Response
     */
    public function playersList(ShiniPlayerRepository $shiniPlayerRepository): Response
    {
        return $this->render('shini_staff/list_players.html.twig', ['shini_players' => $shiniPlayerRepository->findAll()]);
    }
}
