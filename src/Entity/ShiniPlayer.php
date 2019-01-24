<?php

namespace App\Entity;


use App\EntityTrait\shiniPeopleTrait;
use App\ImageSaver\ImageSaverTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use Symfony\Component\Security\Core\User\UserInterface;


/**
 * @ORM\Entity(repositoryClass="App\Repository\ShiniPlayerRepository")
 * @UniqueEntity(fields={"email"},errorPath="email",message="cet email existe dèjà !")
 * @UniqueEntity(fields={"nickName"},errorPath="nickName",message="Ce pseudo existe déjà !")
 */
class ShiniPlayer implements UserInterface
{

    use shiniPeopleTrait;
    use ImageSaverTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cardCode;

    /**
     * @ORM\OneToMany(targetEntity="ShiniCard",mappedBy="player")
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
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $confirmation_token;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $confirmed_at;

    /**
     * ShiniPlayer constructor.
     * @param string $role
     * @throws \Exception
     */
    public function __construct(string $role = 'ROLE_PLAYER')
    {
        #$this->cards = new ArrayCollection();
        #$this->account = new ArrayCollection();
        $this->games = new ArrayCollection();
        $this->roles = new ArrayCollection();
        $this->addRole($role);
        $this->folder = 'player';
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function setCards($cards = null)
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

    public function getConfirmationToken(): ?string
    {
        return $this->confirmation_token;
    }

    public function setConfirmationToken($confirmation_token): self
    {
        $this->confirmation_token = $confirmation_token;

        return $this;
    }

    public function getConfirmedAt(): ?\DateTimeInterface
    {
        return $this->confirmed_at;
    }

    public function setConfirmedAt(\DateTimeInterface $confirmed_at): self
    {
        $this->confirmed_at = $confirmed_at;

        return $this;
    }
}
