<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\ShiniPlayer;
use App\Entity\ShiniCenter;
/**
 * @ORM\Entity(repositoryClass="App\Repository\ShiniCardRepository")
 */
class ShiniCard
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $rfid;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $QRCode;

    /**
     * @ORM\Column(type="integer")
     */
    private $motif;

    /**
     * @ORM\OneToMany(targetEntity="ShiniPlayer",mappedBy="cards")
     */
    private $player;

    /**
     * @ORM\ManyToOne(targetEntity="ShiniCenter",inversedBy="cards")
     */
    private $center;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRfid(): ?int
    {
        return $this->rfid;
    }

    public function setRfid(int $rfid): self
    {
        $this->rfid = $rfid;

        return $this;
    }

    public function getQRCode(): ?int
    {
        return $this->QRCode;
    }

    public function setQRCode(?int $QRCode): self
    {
        $this->QRCode = $QRCode;

        return $this;
    }

    public function getMotif(): ?int
    {
        return $this->motif;
    }

    public function setMotif(int $motif): self
    {
        $this->motif = $motif;

        return $this;
    }

    public function getPlayer(): ?int
    {
        return $this->player;
    }

    public function setPlayer(int $player): self
    {
        $this->player = $player;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCenter()
    {
        return $this->center;
    }

    /**
     * @param mixed $center
     * @return ShiniCard
     */
    public function setCenter($center)
    {
        $this->center = $center;
        return $this;
    }

}
