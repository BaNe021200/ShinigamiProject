<?php
/**
 * Created by PhpStorm.
 * User: connector
 * Date: 09/01/2019
 * Time: 12:39
 */

namespace App\DoctrineSubscriber;


use App\Entity\ShiniPlayer;
use App\Entity\ShiniStaff;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;

class EmptyPasswordListener
{
    public function preUpdate(LifecycleEventArgs $event)
    {
        $entity = $event->getObject();
        if($entity instanceof ShiniPlayer || $entity instanceof ShiniStaff) {
            if(empty($entity->getPassword())) {
                $repo = $event->getEntityManager()->getRepository(get_class($entity));
                $oldPassword = $repo->findPassword($entity->getId());

                $entity->setPassword($oldPassword);
            }
        }
    }
}