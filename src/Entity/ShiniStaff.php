<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\ShiniOffer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ShiniStaffRepository")
 */
class ShiniStaff
{
    const DIRECTORY = 'staff';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="array")
     */
    private $roles = [];

    /**
     * @ORM\OneToMany(targetEntity="ShiniOffer",mappedBy="staffAdviser")
     */
    private $offers;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     */
    private $staffImageName;

    /**
     * ShiniStaff constructor.
     *
     */
    public function __construct()
    {
        $this->offers = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getRoles(): ?array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @param mixed $role
     * @return ShiniPlayer
     */
    public function addRole($role)
    {
        $this->roles[] = $role;
        return $this;
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

    /**
     * @return mixed
     */
    public function getStaffImageName()
    {
        return $this->staffImageName;
    }

    /**
     * @param mixed $staffImageName
     */
    public function setStaffImageName($staffImageName): self
    {
        $this->staffImageName = $staffImageName;
        return $this;
    }
}
