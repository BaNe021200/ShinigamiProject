<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 24/01/2019
 * Time: 17:34
 */

namespace App\Service\Twig;


use App\Entity\ShiniAdmin;
use App\Entity\ShiniCenter;
use App\Entity\ShiniOffer;
use App\Entity\ShiniPlayer;
use App\Entity\ShiniStaff;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;


class TwigExtension extends AbstractExtension
{

    const NB_SUMMARY_CHAR = 80;
    private $em;

    public function __construct(EntityManagerInterface $manager)
    {
        # Récupération de Doctrine
        $this->em = $manager;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('summary', function($text) {

                # Suppression des Balises HTML
                $string = strip_tags($text);

                # Si mon string est supérieur à 170, je continue
                if(strlen($string) > self::NB_SUMMARY_CHAR) {

                    # Je coupe ma chaine à 170
                    $stringCut = substr($string, 0, self::NB_SUMMARY_CHAR);
                    $string = substr($stringCut, 0, strrpos($stringCut, ' ')). '...';
                }

                return $string;

            },['is_safe' => ['html']])
        ];
    }
    

    public function getFunctions()
    {
        return [
            new TwigFunction('getEntityPrefixRoute', [$this, 'getPrefix']),
            new TwigFunction('getOffersOnLine', function() {
                return $this->em->getRepository(ShiniOffer::class)->findOnFirstPage();
            })
        ];
    }

    /**
     * Find route prefix of entity to render in twig.
     *
     * @param $shiniEntity
     * @return string
     */
    public function getPrefix($shiniEntity)
    {
        $prefix = '';
        // Suppress doctrine prefix 'Proxies\__CG__'  from name class.
        switch (mb_substr(get_class($shiniEntity), 15))
        {
            case ShiniAdmin::class:
                $prefix ='shini.admin';
                break;
            case ShiniStaff::class:;
                $prefix ='shini.staff';
                break;
            case ShiniPlayer::class:;
                $prefix ='shini.player';
                break;
            case ShiniCenter::class:;
                $prefix ='shini.center';
                break;
            case ShiniOffer::class:;
                $prefix ='shini.offer';;
                break;
            default:
                $prefix ='unknown';
                break;
        }
        return $prefix;
    }
}