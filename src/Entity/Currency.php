<?php

namespace App\Entity;

use App\Repository\CurrencyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CurrencyRepository::class)]
#[ORM\Index(columns: ['name'])]
#[ORM\Index(columns: ['origin_id'])]
#[ORM\Index(columns: ['origin_number'])]
#[ORM\Index(columns: ['origin_code'])]
class Currency
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 16)]
    private ?string $origin_id = null;

    #[ORM\Column]
    private ?int $origin_number = null;

    #[ORM\Column(length: 6)]
    private ?string $origin_code = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'currency', targetEntity: ExchangeRate::class, orphanRemoval: true)]
    private Collection $exchangeRates;



    public function __construct()
    {
        $this->exchangeRates = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOriginId(): ?string
    {
        return $this->origin_id;
    }

    public function setOriginId(string $origin_id): self
    {
        $this->origin_id = $origin_id;

        return $this;
    }

    public function getOriginNumber(): ?int
    {
        return $this->origin_number;
    }

    public function setOriginNumber(int $origin_number): self
    {
        $this->origin_number = $origin_number;

        return $this;
    }

    public function getOriginCode(): ?string
    {
        return $this->origin_code;
    }

    public function setOriginCode(string $origin_code): self
    {
        $this->origin_code = $origin_code;

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

    /**
     * @return Collection<int, ExchangeRate>
     */
    public function getExchangeRates(): Collection
    {
        return $this->exchangeRates;
    }

    public function addExchangeRate(ExchangeRate $exchangeRate): self
    {
        if (!$this->exchangeRates->contains($exchangeRate)) {
            $this->exchangeRates->add($exchangeRate);
            $exchangeRate->setCurrency($this);
        }

        return $this;
    }

    public function removeExchangeRate(ExchangeRate $exchangeRate): self
    {
        if ($this->exchangeRates->removeElement($exchangeRate)) {
            // set the owning side to null (unless already changed)
            if ($exchangeRate->getCurrency() === $this) {
                $exchangeRate->setCurrency(null);
            }
        }

        return $this;
    }
}
