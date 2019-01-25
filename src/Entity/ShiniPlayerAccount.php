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
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $player;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $confirmation_token;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $confirmed_at;

    /**
     * ShiniPlayerAccount constructor.
     * @param \App\Entity\ShiniPlayer $player
     */
    public function __construct(ShiniPlayer $player)
    {
        $this->offers = new ArrayCollection();

        $this->player = $player;
        $player->setAccount($this);
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
