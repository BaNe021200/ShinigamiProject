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
 *
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
        return $this->render('profile.html.twig', ['user' => $admin]);
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

    /**
     *
     * Create a new Admin (for Debug only, You need to disable the access_control from security.yaml)
     *
     * @param Request $request
     * @return Response
     *
     * @Route("/new", name=".new")
     */
    public function new(Request $request):Response
    {
        $user = $this->getUser();
        if ($user) return $this->redirectToRoute('shini.admin.connect');

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

            return $this->redirectToRoute('shini.admin.profile');
        }

        return $this->render('profile.html.twig',[
            'form'=> $form->createView()
        ]);
    }


    /**
 *
 * list Admin (for Debug only, You need to disable the access_control from security.yaml)
 *
 * @param Request $request
 * @return Response
 *
 * @Route("/testlist", name=".testlist")
 */
    public function testlist(Request $request):Response
    {

        return $this->render('list.html.twig');
    }

    /**
     *
     * Create an Admin (for Debug only, You need to disable the access_control from security.yaml)
     *
     * @param Request $request
     * @return Response
     *
     * #@Route("/create", name=".create")
     */
    public function createAdmin(Request $request):Response
    {
        $admin = new ShiniStaff();
        $admin->setNickName('Admin');
        $admin->setName('Toto');
        $admin->setLastname('Zéro');
        $admin->setPassword('Aa!00000');
        $admin->setEmail('toto@zero.com');
        $admin->addRole('ROLE_ADMIN');
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($admin);
        $entityManager->flush();
        return $this->redirectToRoute('shini.admin.connect');
    }
}
