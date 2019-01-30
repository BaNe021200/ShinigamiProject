<?php

namespace App\Entity;

use App\Service\ImageSaver\ImageSaverTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Cocur\Slugify\Slugify;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ShiniOfferRepository")
 */
class ShiniOffer
{
    use ImageSaverTrait;
    const ASSET_FOLDER = 'offer';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateEnd;

    /**
     * @ORM\Column(type="boolean", options={"default":0})
     */
    private $onfirstpage;


    /**
     * @ORM\ManyToOne(targetEntity="ShiniStaff", inversedBy="offers")
     * @ORM\JoinColumn(nullable=true)
     */
    private $staffAdviser;

    /**
     * @ORM\ManyToMany(targetEntity="ShiniPlayerAccount", mappedBy="offers")
     *@ORM\JoinColumn(nullable=true)
     */
    private $accounts;

    /**
     * ShiniOffer constructor.
     */
    public function __construct()
    {
        $this->accounts = new ArrayCollection();
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
    public function setDateEnd($dateEnd): self
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

    /**
     * @return mixed
     */
    public function getAccounts()
    {
        return $this->accounts;
    }

    /**
     * @param $account
     * @return ShiniOffer
     */
    public function addAccounts($account): self
    {
        $this->accounts[] = $account;
        return $this;
    }

    /**
     * @param mixed $accounts
     * @return ShiniOffer
     */
    public function setAccounts($accounts): self
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
    public function setStaffAdviser($staffAdviser): self
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
