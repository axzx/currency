<?php

namespace App\Entity;

use App\Repository\CurrencyRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CurrencyRepository::class)
 * @ORM\Table(name="app_currency")
 * @UniqueEntity("code")
 * @ORM\HasLifecycleCallbacks()
 */
class Currency
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"currency"})
     */
    private $id;

    /**
     * @Assert\Length(max="90")
     * @ORM\Column(type="string", length=90, nullable=true)
     * @Groups({"currency"})
     */
    private $name;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max="3")
     * @ORM\Column(type="string", length=3, unique=true)
     * @Groups({"currency"})
     */
    private $code;

    /**
     * @ORM\Column(type="decimal", precision=14, scale=10, nullable=true)
     * @Groups({"currency"})
     */
    private $rate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $rateChangeAt;

    public function __toString(): string
    {
        return sprintf('%s (%s)', $this->getName(), $this->getCode());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getRate(): ?float
    {
        return $this->rate;
    }

    public function setRate(?float $rate): self
    {
        $this->rate = $rate;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getRateChangeAt(): ?\DateTimeInterface
    {
        return $this->rateChangeAt;
    }

    public function setRateChangeAt(?\DateTimeInterface $rateChangeAt): self
    {
        $this->rateChangeAt = $rateChangeAt;

        return $this;
    }

    public function updateRateChangeAt(): self
    {
        $this->rateChangeAt = new \DateTime();

        return $this;
    }
}
