<?php

namespace App\Entity;

use App\ImageSaver\ImageSaverTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\ShiniPlayerAccount;
use App\Entity\ShiniStaff;
use Cocur\Slugify\Slugify;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ShiniOffersRepository")
 */
class ShiniOffer
{
    use ImageSaverTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
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
     * @ORM\JoinColumn(nullable=true)
     */
    private $staffAdviser;

    /**
     * @ORM\ManyToMany(targetEntity="ShiniPlayerAccount",mappedBy="offers")
     *@ORM\JoinColumn(nullable=true)
     */
    private $accounts;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="boolean")
     */
    private $onfirstpage;

    /**
     * ShiniOffer constructor.
     */
    public function __construct()
    {
        $this->accounts = new ArrayCollection();
        $this->folder = 'offer';
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getPrice():?int
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     * @return ShiniOffer
     */
    public function setPrice(?int $price)
    {
        $this->price = $price;
        return $this;
    }
    public function getFormattedPrice():string
    {
        return number_format($this->price, 0, '',' ');
    }

    /**
     * @return mixed
     */
    public function getDateEnd()
    {
        return $this->dateEnd;
    }

    /**
     * @param mixed $dateEnd
     * @return ShiniOffer
     */
    public function setDateEnd($dateEnd)
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug():string
    {
        return (new Slugify())->slugify($this->name);
    }

    public function getOnfirstpage(): ?bool
    {
        return $this->onfirstpage;
    }

    public function setOnfirstpage(bool $onfirstpage): self
    {
        $this->onfirstpage = $onfirstpage;

        return $this;
    }

}
