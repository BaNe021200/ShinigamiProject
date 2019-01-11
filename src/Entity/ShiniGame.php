<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\ShiniCenter;
use App\Entity\ShiniPlayer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ShiniGameRepository")
 */
class ShiniGame
{
    const DIRECTORY = 'game';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateBegin;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateEnd;

    /**
     * @ORM\ManyToMany(targetEntity="ShiniCenter",mappedBy="games")
     */
    private $centers;

    /**
     * @ORM\ManyToMany(targetEntity="ShiniPlayer",mappedBy="games")
     */
    private $players;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     */
    private $gameImageName;

    /**
     * ShiniGame constructor.
     * @param $centers
     */
    public function __construct()
    {
        $this->centers = new ArrayCollection();
        $this->players = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getDateBegin()
    {
        return $this->dateBegin;
    }

    /**
     * @param mixed $dateBegin
     * @return ShiniGame
     */
    public function setDateBegin($dateBegin)
    {
        $this->dateBegin = $dateBegin;
        return $this;
    }

    public function getDateEnd(): ?\DateTimeInterface
    {
        return $this->dateEnd;
    }

    public function setDateEnd(\DateTimeInterface $dateEnd): self
    {
        $this->dateEnd = $dateEnd;

        return $this;
    }

    public function getPlayers(): ?array 
    {
        return $this->players;
    }

    public function setPlayers($players): self
    {
        $this->players = $players;

        return $this;
    }
    
    public function addPlayer($players): self
    {
        $this->players = $players;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCenters()
    {
        return $this->centers;
    }

    /**
     * @param mixed $centers
     * @return ShiniGame
     */
    public function setCenters($centers)
    {
        $this->centers = $centers;
        return $this;
    }

    /**
     * @param mixed $center
     * @return ShiniGame
     */
    public function addCenters($center)
    {
        $this->centers[] = $center;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getGameImageName()
    {
        return $this->gameImageName;
    }

    /**
     * @param mixed $gameImageName
     * @return ShiniGame
     */
    public function setGameImageName($gameImageName)
    {
        $this->gameImageName = $gameImageName;
        return $this;
    }
}
