<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 30/01/2019
 * Time: 19:34
 */

namespace App\Security;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;

class AccessDeniedHandler extends AbstractController implements AccessDeniedHandlerInterface
{
    public function handle(Request $request, AccessDeniedException $accessDeniedException)
    {
        // ...
        $this->addFlash('danger','Vous n\'avez pas les droits pour accéder à cette route');
        //dump($this->getParameter('referrer'));
        return $this->render('shini.index', ['access' => 'denied']);
        //return new Response('Impossible d\'accéder à cette page', 403);
    }
}