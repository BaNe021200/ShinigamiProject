<?php

namespace App\Controller;


use App\Entity\ShiniAdmin;
use App\Form\ShiniAdminType;
use App\Form\ShiniStaffType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Admin routes reachable with ROLE_ADMIN.
 *
 *
 * @Route("/admin", name="shini.admin")
 *
 */
class AdminController extends AbstractController
{
    /**
     * Admin show/edit his own profile.
     *
     * @param Request $request
     * @return Response
     *
     * @Route("/", name=".profile", methods={"GET"})
     */
    public function showEditProfile(): Response
    {
        $admin = $this->getUser();
        return $this->render('entity/user/profile.html.twig');
    }

    /**
     * Create an Admin (for debug only, when access_control in security.yaml is disabled)
     *
     * @param Request $request
     * @return Response
     *
     * #@Route("/new-debug", name=".debug.new")
     */
    public function new(Request $request):Response
    {
        $admin = new ShiniAdmin();

        $form = $this->createForm(ShiniAdminType::class, $admin, [
            'validation_groups'=>['insertion', 'Default']
        ]);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $em= $this->getDoctrine()->getManager();
            $em->persist($admin);
            $em->flush();
            $this->addFlash('success','Administrateur créé');

            return $this->redirectToRoute('shini.admin.profile');
        }

        return $this->render('entity/user/profile.html.twig',[
            'form'=> $form->createView()
        ]);
    }
}
