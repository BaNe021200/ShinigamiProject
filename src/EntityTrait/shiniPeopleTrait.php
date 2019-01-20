<?php
/**
 * Created by PhpStorm.
 * User: connector
 * Date: 08/01/2019
 * Time: 10:34
 */

namespace App\EntityTrait;

use Symfony\Component\Validator\Constraints as Assert;

trait shiniPeopleTrait
{
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull(message="N'oubliez pas vote prénom")
     * @Assert\Length(max="50")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull(message="N'oubliez pas d'entrer votre pseudo")
     */
    private $nickName;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     *
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     *
     */
    private $phone;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     */
    private $birthday;

    /**
     * @ORM\Column(type="array")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\NotBlank(message="N'oubliez pas d'entrer votre mot de passe", groups={"insertion"})
     * @Assert\Regex(pattern="/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[-+!*$@%_#&])([!@#$%^&*\w]{8,20})$/",message="votre mot de passe est invalide : il doit contenir au moins une majuscule, une minuscule et un de ces caractères spéciaux (!@#$%^&*)")
     * @Assert\Length(min="8", minMessage="votre mot de passe doit contenir {{ limit }} caractères au minimum et 20 au max",max="20",maxMessage="votre mot de passe est trop long il ne doit pas contenir plus de {{ limit }} caractères")
     *
     */
    private $password;



    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="N'oubliez pas d'entrer votre email")
     * @Assert\Email(message="Veuillez entrer un email valide")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     *
     */
    private $postal_code;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull(message="N'oubliez pas d'entrer votre nom")
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     */
    private $city;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getNickName(): ?string
    {
        return $this->nickName;
    }

    public function setNickName(string $nickName): self
    {
        $this->nickName = $nickName;
        return $this;
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

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;
        return $this;
    }

    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(\DateTimeInterface $birthday): self
    {
        $this->birthday = $birthday;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRoles()
    {
        return $this->roles->toArray();
    }

    /**
     * @param mixed $roles
     * @return mixed
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * @param mixed $role
     * @return mixed
     */
    public function addRole($role)
    {
        $this->roles[] = $role;
        return $this;
    }
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postal_code;
    }

    public function setPostalCode(string $postal_code): self
    {
        $this->postal_code = $postal_code;
        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;
        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;
        return $this;
    }
}