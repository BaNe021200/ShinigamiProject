<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\ShiniPlayerAccount;
use App\Entity\ShiniCard;
use App\Entity\ShiniGame;


/**
 * @ORM\Entity(repositoryClass="App\Repository\ShiniPlayerRepository")
 */
class ShiniPlayer
{
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
     * @ORM\Column(type="string", length=255)
     */
    private $nickName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $phone;

    /**
     * @ORM\Column(type="datetime")
     */
    private $birthday;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cardCode;

    /**
     * @ORM\ManyToOne(targetEntity="ShiniCard",inversedBy="player")
     */
    private $cards;

    /**
     * @ORM\OneToOne(targetEntity="ShiniPlayerAccount")
     *
     */
    private $account;

    /**
     * @ORM\ManyToMany(targetEntity="ShiniGame", inversedBy="players")
     *
     */
    private $games;

    /**
     * @ORM\Column(type="array")
     */
    private $roles = [];

    /**
     * ShiniPlayer constructor.
     *
     */
    public function __construct()
    {
        $this->cards = new ArrayCollection();
        $this->account = new ArrayCollection();
        $this->games = new ArrayCollection();
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

    public function getNickName(): ?string
    {
        return $this->nickName;
    }

    public function setNickName(string $nickName): self
    {
        $this->nickName = $nickName;

        return $this;
    }


    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(\DateTimeInterface $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getCardCode(): ?int
    {
        return $this->cardCode;
    }

    public function setCardCode(?int $cardCode): self
    {
        $this->cardCode = $cardCode;

        return $this;
    }

    public function getCards(): ?array
    {
        return $this->cards;
    }

    public function setCards(?array $cards): self
    {
        $this->cards = $cards;

        return $this;
    }

    public function getAccount(): ?int
    {
        return $this->account;
    }

    public function setAccount(int $account): self
    {
        $this->account = $account;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param mixed $roles
     * @return ShiniPlayer
     */
    public function setRoles($roles)
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


}
