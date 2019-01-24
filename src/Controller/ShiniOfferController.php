<?php

namespace App\Controller;

use App\Entity\ShiniOffer;
use App\Form\ShiniOffer1Type;
use App\Repository\ShiniOffersRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/shiniStaff/offer")
 */
class ShiniOfferController extends AbstractController
{
    /**
     * @Route("/", name="shini.offer.index", methods={"GET"})
     * @param Request $request
     * @param ShiniOffersRepository $shiniOffersRepository
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function index(Request $request,ShiniOffersRepository $shiniOffersRepository, PaginatorInterface $paginator): Response
    {
        return $this->render('shini_offer/index.html.twig', ['shini_offers' => $paginator->paginate($shiniOffersRepository->findAllQuery(),$request->query->getInt('page',1),5)]);
    }

    /**
     * @Route("/new", name="shini.offer.new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        /*$shiniOffer = new ShiniOffer();
        $form = $this->createForm(ShiniOffer1Type::class, $shiniOffer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($shiniOffer);
            $entityManager->flush();

            return $this->redirectToRoute('shini.offer.index');
        }

        return $this->render('shini_offer/new.html.twig', [
            'shini_offer' => $shiniOffer,
            'form' => $form->createView(),
        ]);*/
    }

    /**
     * @Route("/{id}", name="shini.offer.show", methods={"GET"})
     * @param ShiniOffer $shiniOffer
     * @return Response
     */
    public function show(ShiniOffer $shiniOffer): Response
    {
        return $this->render('shini_offer/show.html.twig', ['shini_offer' => $shiniOffer]);
    }

    /**
     * @Route("/{id}/edit", name="shini.offer.edit", methods={"GET","POST"})
     * @param Request $request
     * @param ShiniOffer $shiniOffer
     * @return Response
     */
    public function edit(Request $request, ShiniOffer $shiniOffer): Response
    {
        $form = $this->createForm(ShiniOffer1Type::class, $shiniOffer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

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

            return $this->redirectToRoute('shini.offer.index', ['id' => $shiniOffer->getId()]);
        }

        return $this->render('shini_offer/edit.html.twig', [
            'shini_offer' => $shiniOffer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="shini.offer.delete", methods={"DELETE"})
     * @param Request $request
     * @param ShiniOffer $shiniOffer
     * @return Response
     */
    public function delete(Request $request, ShiniOffer $shiniOffer): Response
    {
        if ($this->isCsrfTokenValid('delete'.$shiniOffer->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($shiniOffer);
            $entityManager->flush();
        }

        return $this->redirectToRoute('shini.offer.index');
    }
}
