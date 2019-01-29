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
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;


class TwigExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('getEntityPrefixRoute', [$this, 'getPrefix'])
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