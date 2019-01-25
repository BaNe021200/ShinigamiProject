<?php

namespace App\Controller;

use App\Entity\ShiniPlayer;
use App\Form\ShiniSignInType;
use App\Form\ShiniLoginType;
use App\Repository\ShiniOffersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * @Route("/", name="shini")
 */
class IndexController extends AbstractController
{
    /**
     * @Route("/", name=".index")
     * @param ShiniOffersRepository $offersRepository
     * @return Response
     */
    public function index(ShiniOffersRepository $offersRepository):Response
    {
        $offerOnLine = $offersRepository->findOnLine();

        return $this->render('page/index.html.twig', [
            'onLines'=>$offerOnLine,
            'current'=>'index'
        ]);
    }

    /**
     * @Route("/about",name=".about")
     */
    public function about()
    {
       return $this->render('page/about.html.twig',[
           'current' =>'about'
       ]);
    }

    /**
     * @Route("/contact", name=".contact")
     */
    public function contact()
    {
        return $this->render('page/contact.html.twig',['current'=>'contact']);
    }

}
