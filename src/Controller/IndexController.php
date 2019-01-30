<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/", name="shini")
 */
class IndexController extends AbstractController
{
    /**
     * @Route("/", name=".index")
     * @return Response
     */
    public function index():Response
    {
        return $this->render('page/index.html.twig', [
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
