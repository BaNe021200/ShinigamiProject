<?php

namespace App\Entity;

use App\Service\ImageSaver\ImageSaverTrait;
use App\EntityTrait\shiniPeopleTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;


/**
 * @ORM\Entity(repositoryClass="App\Repository\ShiniStaffRepository")
 * @UniqueEntity(fields={"email"},errorPath="email",message="cet email existe dèjà !")
 * @UniqueEntity(fields={"nickName"},errorPath="nickName",message="Ce pseudo existe déjà !")
 */
class ShiniAdmin implements UserInterface
{
    use shiniPeopleTrait;
    use ImageSaverTrait;

    const ASSET_FOLDER = 'admin';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * ShiniStaff constructor.
     * @param string $role
     */
    public function __construct(string $role = 'ROLE_ADMIN')
    {
        $this->addRole($role);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSalt()
    {
        return null;
    }

    public function getUsername()
    {
        return $this->email;
    }

    public function eraseCredentials()
    {

    }
}
