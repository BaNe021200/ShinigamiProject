<?php

namespace App\Entity;


use App\EntityTrait\shiniPeopleTrait;
use App\Service\ImageSaver\ImageSaverTrait;
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

    const ASSET_FOLDER = 'player';

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
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     * @var ShiniPlayerAccount|null $account
     */
    private $account;

    /**
     * @ORM\ManyToMany(targetEntity="ShiniGame", inversedBy="players")
     * @ORM\JoinColumn(nullable=true)
     *
     */
    private $games;



    /**
     * ShiniPlayer constructor.
     * @param string $role
     * @throws \Exception
     */
    public function __construct(string $role = 'ROLE_PLAYER')
    {
        #$this->cards = new ArrayCollection();

        $this->games = new ArrayCollection();
        $this->roles = new ArrayCollection();
        $this->addRole($role);
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
     * @return ShiniPlayerAccount
     */
    public function getAccount(): ?ShiniPlayerAccount
    {
        return $this->account;
    }

    /**
     * @param ShiniPlayerAccount $account
     * @return ShiniPlayer
     */
    public function setAccount(?ShiniPlayerAccount $account): ShiniPlayer
    {
        $this->account = $account;
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
}
