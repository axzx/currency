<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`app_user`")
 * @UniqueEntity("email")
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\Valid()
     * @ORM\OneToMany(targetEntity=UserCurrency::class, mappedBy="user", cascade={"persist"})
     */
    private $currencies;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max="180")
     * @Assert\Email()
     * @Groups({"user"})
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max="90")
     * @Groups({"user"})
     * @ORM\Column(type="string", length=90)
     */
    private $firstname;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max="90")
     * @Groups({"user"})
     * @ORM\Column(type="string")
     */
    private $lastname;

    /**
     * @Assert\NotBlank()
     * @Assert\Regex("/^[1-9]([0-9]{8})$/", message="Numer musi mieć 9 cyfr i nie może zaczynać się od 0.")
     * @Groups({"user"})
     * @ORM\Column(type="integer")
     */
    private $phone;

    /**
     * @Assert\NotBlank()
     * @Assert\LessThan("-18 years")
     * @Groups({"user"})
     * @ORM\Column(type="date")
     */
    private $birthday;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $confirmedAt;

    /**
     * @Groups({"register"})
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    private $token;

    /**
     * @Groups({"register"})
     * @ORM\Column(type="string", length=32, unique=true)
     */
    private $uuid;

    public function __construct()
    {
        $this->currencies = new ArrayCollection();
        $this->token = bin2hex(random_bytes(16));
        $this->uuid = bin2hex(random_bytes(16));
    }

    public function __toString(): string
    {
        return trim($this->getFirstname().' '.$this->getLastname());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(?\DateTimeInterface $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getConfirmedAt(): ?\DateTimeInterface
    {
        return $this->confirmedAt;
    }

    public function setConfirmedAt(?\DateTimeInterface $confirmedAt): self
    {
        $this->confirmedAt = $confirmedAt;

        return $this;
    }

    /**
     * @return Collection|UserCurrency[]
     */
    public function getCurrencies(): Collection
    {
        return $this->currencies;
    }

    public function addCurrency(UserCurrency $currency): self
    {
        if (!$this->currencies->contains($currency)) {
            $this->currencies[] = $currency;
            $currency->setUser($this);
        }

        return $this;
    }

    public function removeCurrency(UserCurrency $currency): self
    {
        if ($this->currencies->removeElement($currency)) {
            // set the owning side to null (unless already changed)
            if ($currency->getUser() === $this) {
                $currency->setUser(null);
            }
        }

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(?string $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }
}
