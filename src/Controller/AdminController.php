<?php

namespace App\Controller;


use App\Entity\ShiniStaff;
use App\Form\ShiniStaffEditType;
use App\Form\ShiniStaffType;
use App\Repository\ShiniCenterRepository;
use App\Repository\ShiniStaffRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Admin actions usable with ROLE_ADMIN.
 * Admin is a ShiniStaff with specific ROLE_ADMIN.
 *
 * @Route("/admin", name="shini.admin")
 * @Security("has_role('ROLE_ADMIN')")
 */
class AdminController extends AbstractController
{
    /**
     * Show own profile to the connected admin.
     *
     * @param Request $request
     * @return Response
     *
     * @Route("/", name=".connect", methods={"GET"})
     */
    public function connect(): Response
    {
        $admin = $this->getUser();
        return $this->render('user/profile.html.twig', ['user' => $admin]);
    }

    /**
     * Admin edit his profile.
     *
     * @param Request $request
     * @param ShiniStaff
     * @return Response
     *
     * @Route("/{id}/edit", name=".edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ShiniStaff $shiniStaff): Response
    {
        $form = $this->createForm(ShiniStaffEditType::class, $shiniStaff);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success','Votre profil est modifié');

            return $this->redirectToRoute('shini.staff.index', ['id' => $shiniStaff->getId()]);
        }

        return $this->render('staff/edit.html.twig', [
            'staff' => $shiniStaff,
            'form' => $form->createView(),
        ]);
    }

}
