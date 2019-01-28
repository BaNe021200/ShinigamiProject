<?php

namespace App\Controller;

use App\Entity\ShiniAdmin;
use App\Entity\ShiniPlayer;
use App\Entity\ShiniStaff;
use App\Form\ShiniStaffType;
use App\Repository\ShiniCenterRepository;
use App\Repository\ShiniStaffRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Staff routes reachable with ROLE_STAFF.
 *
 * @Route("/staff", name="shini.staff")
 */
class StaffController extends AbstractController
{
    /**
     * ShiniStaff Show/edit his own profile
     * Nobody else can edit his profile.
     *
     * @return Response
     *
     * @Route("/", name=".profile", methods={"GET"})
     */
    public function showEditProfile(Request $request): Response
    {
        $user = $this->getUser();
        if($user)
        {
            if (is_a($user, ShiniPlayer::class))
            {
                return $this->redirectToRoute('shini.player.profile');
            }
            else if (is_a($user, ShiniAdmin::class))
            {
                return $this->redirectToRoute('shini.admin.profile');
            }
        }

        $form = $this->createForm(ShiniStaffType::class, $user, [
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

/*            return $this->redirectToRoute('profile.html.twig');*/
        }

        return $this->render('entity/user/profile.html.twig',[
            'form'=> $form->createView()
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
        return $this->render('page/show.html.twig', ['staff' => $staff]);
    }

    /**
     * List all staff (ROLE_STAFF).
     *
     * @param ShiniStaffRepository $rep
     * @return Response
     *
     * @Route("/list", name=".list", methods={"GET"})
     */
    public function list(ShiniStaffRepository $rep): Response
    {
        return $this->render('page/list.html.twig', [
            'items' => $rep->findStaffWithCenter()
            ]);
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

            return $this->redirectToRoute('shini.staff.profile');
        }

        return $this->render('entity/user/new.html.twig',[
            'form'=> $form->createView()
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

        return $this->redirectToRoute('shini.list');
    }
}
