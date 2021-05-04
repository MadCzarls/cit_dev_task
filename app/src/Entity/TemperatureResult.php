<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\TemperatureResultRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TemperatureResultRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class TemperatureResult
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /** @ORM\Column(type="string", length=50) */
    private ?string $country;

    /** @ORM\Column(type="string", length=50) */
    private ?string $city;

    /** @ORM\Column(type="float") */
    private ?float $result;

    /** @ORM\Column(type="datetime") */
    private ?DateTimeInterface $createdAt;

    /** @ORM\Column(type="boolean") */
    private bool $isFromCache = false;

    /**
     * @ORM\PrePersist
     */
    public function onPrePersist(): void
    {
        $this->createdAt = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

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

    public function getResult(): ?float
    {
        return $this->result;
    }

    public function setResult(float $result): self
    {
        $this->result = $result;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function isFromCache(): bool
    {
        return $this->isFromCache;
    }

    public function setIsFromCache(bool $isFromCache): self
    {
        $this->isFromCache = $isFromCache;

        return $this;
    }
}
