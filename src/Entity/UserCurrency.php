<?php

namespace App\Entity;

use App\Repository\UserCurrencyRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserCurrencyRepository::class)
 * @ORM\Table(name="app_user_currency")
 */
class UserCurrency
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="currencies")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $user;

    /**
     * @Assert\NotBlank
     * @ORM\ManyToOne(targetEntity=Currency::class)
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $currency;

    /**
     * @Assert\Length(max="8")
     * @Assert\Range(min="0", max="9999")
     * @ORM\Column(type="decimal", precision=14, scale=10, nullable=true)
     */
    private $alertMax;

    /**
     * @Assert\Length(max="8")
     * @Assert\Range(min="0", max="9999")
     * @ORM\Column(type="decimal", precision=14, scale=10, nullable=true)
     */
    private $alertMin;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $alertSentAt;

    public function __construct(?Currency $currency, User $user)
    {
        $this->currency = $currency;
        $user->addCurrency($this);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCurrency(): ?Currency
    {
        return $this->currency;
    }

    public function setCurrency(?Currency $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getAlertMin(): ?string
    {
        return $this->alertMin;
    }

    public function setAlertMin(?string $alertMin): self
    {
        $this->alertMin = $alertMin;

        return $this;
    }

    public function getAlertMax(): ?string
    {
        return $this->alertMax;
    }

    public function setAlertMax(?string $alertMax): self
    {
        $this->alertMax = $alertMax;

        return $this;
    }

    public function getAlertSentAt(): ?\DateTimeInterface
    {
        return $this->alertSentAt;
    }

    public function setAlertSentAt(?\DateTimeInterface $alertSentAt): self
    {
        $this->alertSentAt = $alertSentAt;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
