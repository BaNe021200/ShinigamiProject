<?php

namespace App\Entity;

use App\Service\ImageSaver\ImageSaverTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ShiniCenterRepository")
 */
class ShiniCenter
{
    use ImageSaverTrait;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=3)
     */
    private $code;

    /**
     * @ORM\OneToMany(targetEntity="ShiniCard",mappedBy="center")
     * @ORM\JoinColumn(nullable=true)
     */
    private $cards;

    /**
     * @ORM\ManyToMany(targetEntity="ShiniGame",inversedBy="centers")
     * @ORM\JoinColumn(nullable=true)
     */
    private $games;

     /**
     * @ORM\OneToMany(targetEntity="ShiniStaff",mappedBy="center")
     */
    private $staff;

    /**
     * ShiniCenter constructor.
     *
     */
    public function __construct()
    {
        $this->cards = new ArrayCollection();
        $this->games = new ArrayCollection();
        $this->staff = new ArrayCollection();
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


    public function getStaff(): ? ArrayCollection
    {
        return $this->staff;
    }

    public function setStaff(int $staff): self
    {
        $this->staff = $staff;

        return $this;
    }
}
