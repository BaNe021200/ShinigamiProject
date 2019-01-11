<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\ShiniPlayerAccount;
use App\Entity\ShiniStaff;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ShiniOffersRepository")
 */
class ShiniOffer
{
    const DIRECTORY = 'offer';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateEnd;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="boolean")
     */
    private $shown;

    /**
     * @ORM\ManyToOne(targetEntity="ShiniStaff",inversedBy="offers")
     */
    private $staffAdviser;

    /**
     * @ORM\ManyToMany(targetEntity="ShiniPlayerAccount",mappedBy="offers")
     *
     */
    private $accounts;

    /**
     * ShiniOffer constructor.
     * @param $accounts
     */
    public function __construct()
    {
        $this->accounts = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDateEnd(): ?array
    {
        return $this->dateEnd;
    }

    public function setDateEnd(array $dateEnd): self
    {
        $this->dateEnd = $dateEnd;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getShown(): ?bool
    {
        return $this->shown;
    }

    public function setShown(bool $shown): self
    {
        $this->shown = $shown;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAccounts()
    {
        return $this->accounts;
    }

    /**
     * @param mixed $accounts
     * @return ShiniOffer
     */
    public function addAccounts($account)
    {
        $this->accounts[] = $account;
        return $this;
    }

    /**
     * @param mixed $accounts
     * @return ShiniOffer
     */
    public function setAccounts($accounts)
    {
        $this->accounts = $accounts;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStaffAdviser()
    {
        return $this->staffAdviser;
    }

    /**
     * @param mixed $staffAdviser
     * @return ShiniOffer
     */
    public function setStaffAdviser($staffAdviser)
    {
        $this->staffAdviser = $staffAdviser;
        return $this;
    }
}
