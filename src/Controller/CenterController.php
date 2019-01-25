<?php

namespace App\Controller;

use App\Entity\ShiniCenter;
use App\Entity\ShiniStaff;
use App\Form\ShiniCenterType;
use App\Form\ShiniStaffEditType;
use App\Repository\ShiniCenterRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Routes related to ShiniGami-Laser centers.
 *
 * @Route("/center", name="shini.center")
 *
 */
class CenterController extends AbstractController
{
    /**
     * List all centers.
     *
     * @return Response
     *
     * @Route("/", name=".list", methods={"GET"})
     */
    public function list(ShiniCenterRepository $rep): Response
    {
        return $this->render('page/list.html.twig', ['items' => $rep->findAll()]);
    }

    /**
     * Show a particular center.
     *
     * @param ShiniCenter $center
     * @return Response
     *
     * @Route("/{id<\d+>}", name=".show", methods={"GET"})
     */
    public function show(ShiniCenter $center): Response
    {
        return $this->render('center/show.html.twig', ['center' => $center]);
    }

    /**
     * Create a new center (ROLE_STAFF only).
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     *
     * @Route("/staff/new", name=".new")
     */
    public function new(Request $request)
    {
        $center = new ShiniCenter();
        $form = $this->createForm(ShiniCenterType::class,$center);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($center);
            $entityManager->flush();
            $this->addFlash('success','Le centre est bien créé');
            return $this->redirectToRoute('shini.center.list');
        }

        return $this->render('center/newCenter.html.twig',[
            'centre'=>$center,
            'form'=> $form->createView()
        ]);
    }

    /**
     * Edit a center (ROLE_STAFF only).

     * @param Request $request
     * @param ShiniCenter $center
     * @return Response
     *
     * @Route("/{id}/staff/edit", name=".edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ShiniCenter $center): Response
    {
        $form = $this->createForm(ShiniStaffEditType::class, $center);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success','Votre profil est modifié');

            return $this->redirectToRoute('shini.center.show', ['id' => $center->getId()]);
        }

        return $this->render('center/edit.html.twig', [
            'center' => $center,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Delete a center (ROLE_STAFF only).
     *
     * @param Request $request
     * @param ShiniStaff $staff
     * @return Response
     *
     * @Route("/{id}/staff/delete", name=".delete", methods={"DELETE"})
     */
    public function delete(Request $request, ShiniStaff $staff): Response
    {
        if ($this->isCsrfTokenValid('delete'.$staff->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($staff);
            $entityManager->flush();
        }

        return $this->redirectToRoute('shini.center.list');
    }
}
