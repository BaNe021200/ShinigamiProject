<?php

namespace App\Controller;

use App\Entity\ShiniOffer;
use App\Form\ShiniOfferType;
use App\Repository\ShiniOfferRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * A sale offer is written, publish, delete by staff (or admin).
 * But can be read by anybody.
 *
 * @Route("/offer",  name="shini.offer")
 *
 */
class OfferController extends AbstractController
{
    /**
     * Show an offer.
     *
     * @param ShiniOffer $offer offer to show
     * @return Response
     *
     * @Route("/{slug}-{id}",name=".show", methods={"GET"},requirements={"slug": "[a-z0-9\-]*"})
     */
    public function show(ShiniOffer $offer):Response
    {
        return $this->render('entity/offer/show.html.twig',[
            'item' => $offer,
            'title' => $offer->getName()
        ]);
    }

    /**
     * List all offers.
     *
     * @param ShiniOfferRepository $shiniOffersRepository
     * @return Response
     *
     * @Route("/", name=".list", methods={"GET"})
     */
    public function list(ShiniOfferRepository $shiniOffersRepository): Response
    {
        return $this->render('entity/offer/list.html.twig', [
            'items' => $shiniOffersRepository->findAll(),
            'title' => 'Nos offres'
        ]);
    }

    /**
     * List offers tagged 'visible'.
     *
     * @param ShiniOfferRepository $offersRepository
     * @return Response
     *
     * @Route("/visible", name=".visible")
     */
    public function visibleOffers(ShiniOfferRepository $offersRepository):Response
    {
        $shiniOffers = $offersRepository->findVisible();

        return $this->render('entity/offer/list.html.twig',[
            'items' =>$shiniOffers
        ]);
    }

    /**
     * Create a new offer (ROLE_STAFF only)
     *
     * @param Request $request
     * @return Response
     *
     * @Route("/staff/new", name=".new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $offer = new ShiniOffer();
        $form = $this->createForm(ShiniOfferType::class, $offer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($offer);
            $entityManager->flush();

            $this->addFlash('success','Votre Offre est créée');
/*            if($offer->getShown())
            {
                $this->addFlash('info','votre offre est publiée');

            }else{
                $this->addFlash('danger','votre offre n\'est pas publiée');
            }*/
            if($offer->getOnfirstpage())
            {
                $this->addFlash('info','votre offre est publiée à la une');
            }else{
                $this->addFlash('danger','votre offre n\'est pas à la une');
            }

            return $this->redirectToRoute('shini.offer.list');
        }

        return $this->render('entity/offer/new.html.twig', [
            'title' => 'Créer une offre',
            'offer' => $offer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Edit an offer (ROLE_STAFF only)
     *
     *
     * @param Request $request
     * @param ShiniOffer $offer offer to edit.
     * @return Response
     *
     * @Route("/{id}/staff/edit", name=".edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ShiniOffer $offer): Response
    {
        $form = $this->createForm(ShiniOfferType::class, $offer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success','Votre Offre est créée');
/*            if($offer->getShown())
            {
                $this->addFlash('info','votre offre est publiée');

            }else{
                $this->addFlash('danger','votre offre n\'est pas publiée');
            }*/
            if($offer->getOnfirstpage())
            {
                $this->addFlash('info','votre offre est publiée à la une');
            }else{
                $this->addFlash('danger','votre offre n\'est pas à la une');
            }

            return $this->redirectToRoute('shini.offer.show', ['item' => $offer]);
        }

        return $this->render('entity/offer/new.html.twig', [
            'offer' => $offer,
            'form' => $form->createView(),
            'title' => $offer->getName()
        ]);
    }

    /**
     * Delete an offer (ROLE_STAFF only)
     *
     * @param Request $request
     * @param ShiniOffer $offer offer to delete.
     * @return Response
     *
     * @Route("/{id}/staff/delete", name=".delete", methods={"GET"})
     */
    public function delete(Request $request, ShiniOffer $offer): Response
    {
/*
        if ($this->isCsrfTokenValid('delete'.$offer->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($offer);
            $entityManager->flush();
        }
 */
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($offer);
        $entityManager->flush();

        $this->addFlash('success','L\'offre'.$offer->getName().' a bien été supprimée');
        return $this->redirectToRoute('shini.offer.list');
    }
}
