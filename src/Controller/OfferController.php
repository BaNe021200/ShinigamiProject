<?php

namespace App\Controller;

use App\Entity\ShiniOffer;
use App\Form\ShiniOfferType;
use App\Repository\ShiniOffersRepository;
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
     * List all offers.
     *
     * @param ShiniOffersRepository $shiniOffersRepository
     * @return Response
     *
     * @Route("/", name=".index", methods={"GET"})
     */
    public function index(ShiniOffersRepository $shiniOffersRepository): Response
    {
        return $this->render('offer/index.html.twig', ['shini_offers' => $shiniOffersRepository->findAll()]);
    }

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
        return $this->render('offer/show.html.twig',[
            'offer' => $offer
        ]);
    }

    /**
     * List offers tagged 'visible'.
     *
     * @param ShiniOffersRepository $offersRepository
     * @return Response
     *
     * @Route("/visible", name=".visible")
     */
    public function visibleOffers(ShiniOffersRepository $offersRepository):Response
    {
        $shiniOffers = $offersRepository->findVisible();

        return $this->render('shini_gami/offers.html.twig',[
            'offer' =>'offer',
            'shini_offers' =>$shiniOffers
        ]);
    }

    /**
     * Create a new offer (ROLE_STAFF only)
     *
     * @Security("has_role('ROLE_STAFF')")
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
            if($offer->getShown())
            {
                $this->addFlash('info','votre offre est publiée');

            }else{
                $this->addFlash('danger','votre offre n\'est pas publiée');
            }
            if($offer->getOnfirstpage())
            {
                $this->addFlash('info','votre offre est publiée à la une');
            }else{
                $this->addFlash('danger','votre offre n\'est pas à la une');
            }

            return $this->redirectToRoute('shini.offer.index');
        }

        return $this->render('offer/new.html.twig', [
            'offer' => $offer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Edit an offer (ROLE_STAFF only)
     *
     * @Security("has_role('ROLE_STAFF')")
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
            if($offer->getShown())
            {
                $this->addFlash('info','votre offre est publiée');

            }else{
                $this->addFlash('danger','votre offre n\'est pas publiée');
            }
            if($offer->getOnfirstpage())
            {
                $this->addFlash('info','votre offre est publiée à la une');
            }else{
                $this->addFlash('danger','votre offre n\'est pas à la une');
            }

            return $this->redirectToRoute('shini.offer.index', ['id' => $offer->getId()]);
        }

        return $this->render('offer/edit.html.twig', [
            'offer' => $offer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Delete an offer (ROLE_STAFF only)
     *
     * @param Request $request
     * @param ShiniOffer $offer offer to delete.
     * @return Response
     *
     * @Route("/{id}/staff/delete", name=".delete", methods={"DELETE"})
     */
    public function delete(Request $request, ShiniOffer $offer): Response
    {
        if ($this->isCsrfTokenValid('delete'.$offer->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($offer);
            $entityManager->flush();
        }

        return $this->redirectToRoute('shini.offer.index');
    }

}
