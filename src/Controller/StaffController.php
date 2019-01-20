<?php

namespace App\Controller;

use App\Entity\ShiniCard;
use App\Entity\ShiniPlayer;
use App\Entity\ShiniStaff;
use App\Form\ShiniStaffEditType;
use App\Form\ShiniStaffType;
use App\Repository\ShiniCenterRepository;
use App\Repository\ShiniPlayerRepository;
use App\Repository\ShiniStaffRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/staff", name="shini.staff")
 */
class StaffController extends AbstractController
{
    const CENTER_CODE = '360';

    /**
     * Show own profile to the connected staff
     *
     * @return Response
     *
     * @Route("/", name=".connect", methods={"GET"})
     */
    public function connect(Request $request): Response
    {
        //TODO: vérifier la connection de l'utilisateur, sinon retourner sur accueil silencieusement
        // Symfony renvoi vers la page de connexion si un utilisateur non accrédité accède à cette page

        $staff = $this->getUser();

        $form = $this->createForm(ShiniStaffType::class, $staff, [
            'validation_groups'=>['Default']
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            //$shiniStaff->setPassword($userPasswordEncoder->encodePassword($shiniStaff,$shiniStaff->getPassword()));
            $em= $this->getDoctrine()->getManager();
            $em->persist($staff);
            $em->flush();
            $this->addFlash('success','Votre profil a été mis à jour');

            return $this->redirectToRoute('profile.html.twig');
        }

        return $this->render('profile.html.twig',[
            'form'=> $form->createView(),
            'user' => $staff
        ]);
    }

    /**
     * Show staff profile page (ROLE_STAFF)
     *
     * @param ShiniStaff $staff
     * @return Response
     *
     * @Route("/{id<\d+>}", name=".show", methods={"GET"})
     */
    public function show(ShiniStaff $staff): Response
    {
        return $this->render('staff/show.html.twig', ['staff' => $staff]);
    }

    /**
     * List all staff (ROLE_STAFF only).
     *
     * @param ShiniStaffRepository $rep
     * @return Response
     *
     * @Route("/list", name=".list", methods={"GET"})
     */
    public function list(ShiniStaffRepository $rep): Response
    {
        return $this->render('staff/index.html.twig', ['shini_staffs' => $rep->findStaffWithCenter()]);
    }

    /**
     * Create a new staff (ROLE_ADMIN only).
     *
     * @param Request $request
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     * @param ShiniCenterRepository $shiniCenterRepository
     * @return Response
     *
     * @Route("/admin/new", name=".new")
     */
    public function new(Request $request, UserPasswordEncoderInterface $userPasswordEncoder,ShiniCenterRepository $shiniCenterRepository):Response
    {
        $shiniStaff = new ShiniStaff();
        //$shiniCenter = $shiniCenterRepository->findAll();
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

            return $this->redirectToRoute('shini.staff.index');
        }

        return $this->render('staff/newStaff.html.twig',[
            'form'=> $form->createView()
        ]);
    }

    /**
     * Staff edit his own page, or admin do it for him.
     *
     * @param Request $request
     * @param ShiniStaff $shiniStaff
     * @return Response
     *
     * @Route("/{id}/edit", name=".edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ShiniStaff $staff): Response
    {
        $form = $this->createForm(ShiniStaffEditType::class, $staff);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {



            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success','Votre profil est modifié');

            return $this->redirectToRoute('shini.staff.index', ['id' => $staff->getId()]);
        }

        return $this->render('staff/edit.html.twig', [
            'staff' => $staff,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Delete a staff (ROLE_ADMIN only).
     *
     * @param Request $request
     * @param ShiniStaff $staff
     * @return Response
     *
     * @Route("/{id}/admin/delete", name=".delete", methods={"DELETE"})
     */
    public function delete(Request $request, ShiniStaff $staff): Response
    {
        if ($this->isCsrfTokenValid('delete'.$staff->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($staff);
            $entityManager->flush();
        }

        return $this->redirectToRoute('shini.staff.list');
    }

}
