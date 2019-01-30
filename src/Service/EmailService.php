<?php
/**
 * Created by PhpStorm.
 * User: connector
 * Date: 22/01/2019
 * Time: 11:08
 */

namespace App\Service;


use App\Entity\ShiniPlayer;
use App\Entity\ShiniPlayerAccount;
use App\Repository\ShiniPlayerRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Swift_Mailer;
use Swift_Message;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class EmailService
 * @package App\Service
 */
class EmailService
{
    /**
     * @var Swift_Mailer
     */
    private $mailer;

    /**
     * @var \Twig_Environment
     */
    private $twig;

    private $em;
    /**
     * @var ObjectManager
     */
    private $manager;

    /**
     * EmailService constructor.
     * @param Swift_Mailer $mailer
     * @param \Twig_Environment $twig
     * @param ObjectManager $manager
     */
    public function __construct(Swift_Mailer $mailer, \Twig_Environment $twig, ObjectManager $manager)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->em = $manager;
    }


    public function emailRegistry(ShiniPlayer $shiniPlayer)
    {
       $token = $shiniPlayer->getAccount()->getConfirmationToken();



        $message = (new Swift_Message('Bienvenue parmi nous'))
            ->setFrom('shiniGamiLaser@mail.com')
            ->setTo($shiniPlayer->getEmail())
            ->setBody(
                $this->twig->render('emails/registration.html.twig',[
                    'player' =>$shiniPlayer,
                    'name' => $shiniPlayer->getNickName(),
                    'token' => $token

                ]),
                'text/html'
            );

        $this->mailer->send($message);
    }

    public function emailContactStaff($data)
    {


        $message = (new Swift_Message('Hello Email'))
            ->setFrom($data['email'])
            ->setTo('benoit.rouley@gmail.com')
            ->setBody(
                $this->twig->render('emails/contactReceived.html.twig',[

                    'data'=>$data
                ]),
                'text/html'
            );

        if( ! $this->mailer->send($message)){
            dd('fail');
        }




    }

    public function emailReInitialization(ShiniPlayer $shiniPlayer)
    {
        $token = $shiniPlayer->getAccount()->getConfirmationToken();



        $message = (new Swift_Message('Hello Email'))
            ->setFrom('shiniGamiLaser@mail.com')
            ->setTo($shiniPlayer->getEmail())
            ->setBody(
                $this->twig->render('emails/reInitializationPassword.html.twig',[
                    'player' =>$shiniPlayer,
                    'name' => $shiniPlayer->getNickName(),
                    'token' => $token

                ]),
                'text/html'
            );

        $this->mailer->send($message);
    }

}
