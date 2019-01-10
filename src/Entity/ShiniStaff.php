<?php

namespace App\Entity;

use App\EntityTrait\shiniPeopleTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\ShiniOffer;
use App\Entity\ShiniPlayerAccount;
use App\Entity\ShiniCard;
use App\Entity\ShiniGame;
use App\Entity\ShiniCenter;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ShiniStaffRepository")
 * @UniqueEntity(fields={"email"},errorPath="email",message="cet email existe dèjà !")
 * @UniqueEntity(fields={"nickName"},errorPath="nickName",message="Ce pseudo existe déjà !")
 */
class ShiniStaff implements UserInterface
{
    use shiniPeopleTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    const DIRECTORY = 'staff';

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;


    /**
     * @ORM\OneToMany(targetEntity="ShiniOffer",mappedBy="staffAdviser")
     */
    private $offers;

    /**
     * @ORM\ManyToOne(targetEntity="ShiniCenter",inversedBy="staff")
     */
    private $center;

    /**
     * ShiniStaff constructor.
     * @param string $role
     */
    public function __construct(string $role = 'ROLE_STAFF')
    {
        $this->offers = new ArrayCollection();
        $this->roles = new ArrayCollection();
        $this->addRole($role);
    }


    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * @return mixed
     */
    public function getOffers()
    {
        return $this->offers;
    }

    /**
     * @param mixed $offers
     * @return ShiniStaff
     */
    public function setOffers($offers)
    {
        $this->offers = $offers;
        return $this;
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

    public function getCenter(): ?ShiniCenter
    {
        return $this->center;
    }

    public function setCenter(ShiniCenter $center): self
    {
        $this->center = $center;

        return $this;
    }
}
