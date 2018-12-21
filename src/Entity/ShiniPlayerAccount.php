<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\ShiniOffer;
use App\Entity\ShiniPlayer;
/**
 * @ORM\Entity(repositoryClass="App\Repository\ShiniAccountRepository")
 */
class ShiniPlayerAccount
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity="ShiniOffer", inversedBy="accounts")
     *
     */
    private $offers ;

    /**
     * @ORM\OneToOne(targetEntity="ShiniPlayer")
     */
    private $player;

    /**
     * ShiniPlayerAccount constructor.
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

    public function getOffers(): ?array
    {
        return $this->offers;
    }

    public function setOffers(?array $offers): self
    {
        $this->offers = $offers;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPlayer()
    {
        return $this->player;
    }

    /**
     * @param mixed $player
     * @return ShiniPlayerAccount
     */
    public function setPlayer($player)
    {
        $this->player = $player;
        return $this;
    }


}
