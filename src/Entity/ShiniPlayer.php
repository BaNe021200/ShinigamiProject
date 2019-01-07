<?php

namespace App\Entity;

use Doctrine\Common\Annotations\Annotation\Required;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\ShiniPlayerAccount;
use App\Entity\ShiniCard;
use App\Entity\ShiniGame;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\ShiniPlayerRepository")
 * @UniqueEntity(fields={"email"},errorPath="email",message="cet email existe dèjà!")
 */
class ShiniPlayer implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull(message="N'oubliez pas d'entrer votre prénom")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull(message="N'oubliez pas d'entrer votre pseudo")
     */
    private $nickName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull(message="N'oubliez pas d'entrer votre adresse")
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\NotNull(message="N'oubliez pas d'entrer votre téléphone")
     */
    private $phone;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotNull(message="N'oubliez pas d'entrer votre date de naissance")
     */
    private $birthday;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cardCode;

    /**
     * @ORM\ManyToOne(targetEntity="ShiniCard",inversedBy="player")
     * @ORM\JoinColumn(nullable=true)
     *
     */
    private $cards ;

    /**
     * @ORM\OneToOne(targetEntity="ShiniPlayerAccount")
     * @ORM\JoinColumn(nullable=true)
     *
     */
    private $account;

    /**
     * @ORM\ManyToMany(targetEntity="ShiniGame", inversedBy="players")
     * @ORM\JoinColumn(nullable=true)
     *
     */
    private $games;

    /**
     * @ORM\Column(type="array")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\NotBlank(message="N'oubliez pas d'entrer votre mot de passe")
     *
     */
    private $password;



    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull(message="N'oubliez pas d'entrer votre email")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=5)
     * @Assert\NotNull(message="N'oubliez pas d'entrer votre cosde postal")
     */
    private $postal_code;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull(message="N'oubliez pas d'entrer votre nom")
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull(message="N'oubliez pas d'entrer votre ville")
     */
    private $city;

    /**
     * ShiniPlayer constructor.
     * @param string $role
     */
    public function __construct(string $role = 'ROLE_PLAYER')
    {
        $this->cards = new ArrayCollection();
        #$this->account = new ArrayCollection();
        $this->games = new ArrayCollection();
        $this->roles = new ArrayCollection();
        $this->addRole($role);
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

    public function getCards()
    {
       return $this->cards;
    }

    public function setCards($cards)
    {
        $this->cards = $cards;

        return $this;
    }

    /**
     * @param $card
     * @return ShiniPlayer
     */
    public function addCard(ShiniCard $card)
    {
        $this->cards[] = $card;
        return $this;
    }

    /*public function getAccount(): ?ArrayCollection
    {
        return $this->account;
    }

    public function setAccount(int $account): self
    {
        $this->account = $account;

        return $this;
    }*/

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


    public function getGames():?ArrayCollection
    {
        return $this->games;
    }

    /**
     * @param mixed $games
     * @return ShiniPlayer
     */
    public function setGames($games)
    {
        $this->games = $games;
        return $this;
    }

    public function addGame(ShiniGame $game)
    {
        $this->games[] = $game;
        return $this;
    }


    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }













    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postal_code;
    }

    public function setPostalCode(string $postal_code): self
    {
        $this->postal_code = $postal_code;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

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
}
