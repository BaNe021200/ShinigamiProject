<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\ShiniCard;
use App\Entity\ShiniGame;
/**
 * @ORM\Entity(repositoryClass="App\Repository\ShiniCenterRepository")
 */
class ShiniCenter
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    const DIRECTORY = 'center';

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=3)
     */
    private $code;

    /**
     * @ORM\OneToMany(targetEntity="ShiniCard",mappedBy="center")
     */
    private $cards;

    /**
     * @ORM\ManyToMany(targetEntity="ShiniGame",inversedBy="centers")
     */
    private $games;

    /**
     * ShiniCenter constructor.
     *
     */
    public function __construct()
    {
        $this->cards = new ArrayCollection();
        $this->games = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
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

    public function getCode(): ?int
    {
        return $this->code;
    }

    public function setCode(int $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCards()
    {
        return $this->cards;
    }

    /**
     * @param mixed $cards
     * @return ShiniCenter
     */
    public function setCards($cards)
    {
        $this->cards = $cards;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getGames()
    {
        return $this->games;
    }

    /**
     * @param mixed $games
     * @return ShiniCenter
     */
    public function setGames($games)
    {
        $this->games = $games;
        return $this;
    }


}
